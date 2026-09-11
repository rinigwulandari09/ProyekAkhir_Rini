<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Desa;
use App\Models\Produksi;
use App\Models\DetailProduksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProduksiController extends Controller
{
    /**
     * Konversi derajat desimal ke format DMS (Degrees, Minutes, Seconds)
     * Contoh: 101.738594 -> 101° 44' 18.9400000" E
     */
    public static function decimalToDms($decimal, bool $isLatitude): string
    {
        if ($decimal === null || !is_numeric($decimal) || $decimal == 0) {
            return '-';
        }

        $abs = abs((float) $decimal);
        $degrees = (int) floor($abs);
        $minutesFloat = ($abs - $degrees) * 60;
        $minutes = (int) floor($minutesFloat);
        $seconds = ($minutesFloat - $minutes) * 60;

        if ($isLatitude) {
            $direction = $decimal >= 0 ? 'N' : 'S';
            return sprintf('%02d° %02d\' %010.7f" %s', $degrees, $minutes, $seconds, $direction);
        } else {
            $direction = $decimal >= 0 ? 'E' : 'W';
            return sprintf('%03d° %02d\' %010.7f" %s', $degrees, $minutes, $seconds, $direction);
        }
    }

    /**
     * Ekstraksi titik tengah / representatif dari kolom area_lahan (GeoJSON)
     */
    public static function extractCoordinates($areaLahan): array
    {
        if (empty($areaLahan)) {
            return ['lng' => null, 'lat' => null, 'lng_dms' => '-', 'lat_dms' => '-'];
        }

        $data = is_string($areaLahan) ? json_decode($areaLahan, true) : $areaLahan;
        if (!is_array($data)) {
            return ['lng' => null, 'lat' => null, 'lng_dms' => '-', 'lat_dms' => '-'];
        }

        $coords = null;
        if (isset($data['geometry']['coordinates'])) {
            $coords = $data['geometry']['coordinates'];
        } elseif (isset($data['features'][0]['geometry']['coordinates'])) {
            $coords = $data['features'][0]['geometry']['coordinates'];
        } elseif (isset($data['coordinates'])) {
            $coords = $data['coordinates'];
        } elseif (isset($data[0])) {
            $coords = $data;
        }

        if (!$coords || !is_array($coords)) {
            return ['lng' => null, 'lat' => null, 'lng_dms' => '-', 'lat_dms' => '-'];
        }

        // Loop ke layer titik terdalam jika format GeoJSON polygon [[[lng, lat], ...]]
        while (isset($coords[0]) && is_array($coords[0]) && isset($coords[0][0]) && is_array($coords[0][0])) {
            $coords = $coords[0];
        }

        $sumLng = 0;
        $sumLat = 0;
        $count = 0;

        foreach ($coords as $pt) {
            $lng = null;
            $lat = null;
            if (is_array($pt) && count($pt) >= 2) {
                // Posisi Indonesia/Riau: Longitude ~101, Latitude ~0
                if (abs((float)$pt[0]) > 60) {
                    $lng = (float)$pt[0];
                    $lat = (float)$pt[1];
                } else {
                    $lat = (float)$pt[0];
                    $lng = (float)$pt[1];
                }
            } elseif (is_object($pt) || (is_array($pt) && (isset($pt['lat']) || isset($pt['lng'])))) {
                $lat = (float)($pt['lat'] ?? 0);
                $lng = (float)($pt['lng'] ?? 0);
            }

            if ($lng !== null && $lat !== null) {
                $sumLng += $lng;
                $sumLat += $lat;
                $count++;
            }
        }

        if ($count === 0) {
            return ['lng' => null, 'lat' => null, 'lng_dms' => '-', 'lat_dms' => '-'];
        }

        $avgLng = $sumLng / $count;
        $avgLat = $sumLat / $count;

        return [
            'lng' => $avgLng,
            'lat' => $avgLat,
            'lng_dms' => self::decimalToDms($avgLng, false),
            'lat_dms' => self::decimalToDms($avgLat, true),
        ];
    }

    /**
     * Mempersiapkan data matriks 12 bulan periode audit RSPO (Sep s/d Agu)
     */
    private function getProduksiData(Request $request): array
    {
        $user = auth()->user();

        // Tahun Audit default adalah tahun saat ini (misal: 2026)
        $tahun = (int) $request->input('tahun', date('Y'));
        if ($tahun < 2020 || $tahun > 2040) {
            $tahun = (int) date('Y');
        }

        $desaId = $request->input('desa_id');
        $search = $request->input('search');

        // Rentang 12 Bulan Audit RSPO: Sep (Tahun - 1) s/d Agu (Tahun)
        $startYear = $tahun - 1;
        $startDate = "{$startYear}-09-01";
        $endDate   = "{$tahun}-08-31";

        // Daftar 12 bulan audit berurutan
        $months = [
            ['key' => "{$startYear}-09", 'label' => 'Sep', 'year' => $startYear],
            ['key' => "{$startYear}-10", 'label' => 'Oct', 'year' => $startYear],
            ['key' => "{$startYear}-11", 'label' => 'Nov', 'year' => $startYear],
            ['key' => "{$startYear}-12", 'label' => 'Dec', 'year' => $startYear],
            ['key' => "{$tahun}-01",     'label' => 'Jan', 'year' => $tahun],
            ['key' => "{$tahun}-02",     'label' => 'Feb', 'year' => $tahun],
            ['key' => "{$tahun}-03",     'label' => 'Mar', 'year' => $tahun],
            ['key' => "{$tahun}-04",     'label' => 'Apr', 'year' => $tahun],
            ['key' => "{$tahun}-05",     'label' => 'May', 'year' => $tahun],
            ['key' => "{$tahun}-06",     'label' => 'Jun', 'year' => $tahun],
            ['key' => "{$tahun}-07",     'label' => 'Jul', 'year' => $tahun],
            ['key' => "{$tahun}-08",     'label' => 'Aug', 'year' => $tahun],
        ];

        // Query Data Lahan
        $lahanQuery = Lahan::with(['petani.desa']);

        // Filter role admin desa
        if ($user->user_role === 'admin' && $user->desa_id) {
            $lahanQuery->whereHas('petani', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        } elseif ($desaId) {
            $lahanQuery->whereHas('petani', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
        }

        if ($search) {
            $lahanQuery->where(function ($q) use ($search) {
                $q->where('lahan_nama', 'like', "%{$search}%")
                  ->orWhere('lahan_lokasi', 'like', "%{$search}%")
                  ->orWhereHas('petani', function ($qp) use ($search) {
                      $qp->where('petani_nama', 'like', "%{$search}%");
                  });
            });
        }

        $lahans = $lahanQuery->orderBy('petani_id', 'asc')
            ->orderBy('lahan_nama', 'asc')
            ->get();

        $lahanIds = $lahans->pluck('lahan_id')->toArray();

        // 1. Ambil data produksi dari detail_produksi (split per plot)
        $detailRecords = DB::table('detail_produksi')
            ->join('produksi', 'detail_produksi.produksi_id', '=', 'produksi.id')
            ->whereIn('detail_produksi.lahan_id', $lahanIds)
            ->whereBetween('produksi.produksi_tanggal', [$startDate, $endDate])
            ->select(
                'detail_produksi.lahan_id',
                'produksi.produksi_tanggal',
                'detail_produksi.jumlah_tbs'
            )
            ->get();

        // 2. Ambil data produksi langsung (jika transaksi lama belum memiliki detail_produksi)
        $directRecords = DB::table('produksi')
            ->leftJoin('detail_produksi', 'produksi.id', '=', 'detail_produksi.produksi_id')
            ->whereNull('detail_produksi.detail_produksi_id')
            ->whereIn('produksi.lahan_id', $lahanIds)
            ->whereBetween('produksi.produksi_tanggal', [$startDate, $endDate])
            ->select(
                'produksi.lahan_id',
                'produksi.produksi_tanggal',
                'produksi.jumlah_tbs'
            )
            ->get();

        // Agregasi produksi bulanan (Kg) per lahan_id
        $monthlyMap = [];
        foreach ($detailRecords->concat($directRecords) as $row) {
            if (!$row->produksi_tanggal) continue;
            $ym = Carbon::parse($row->produksi_tanggal)->format('Y-m');
            $lid = $row->lahan_id;
            if (!isset($monthlyMap[$lid][$ym])) {
                $monthlyMap[$lid][$ym] = 0;
            }
            $monthlyMap[$lid][$ym] += (float) $row->jumlah_tbs;
        }

        // Format baris per plot lahan
        $rows = [];
        $totalSumKg = 0;
        $totalAreaSum = 0;
        $monthlyTotals = array_fill_keys(array_column($months, 'key'), 0);

        foreach ($lahans as $idx => $lahan) {
            $coords = self::extractCoordinates($lahan->area_lahan);
            $areaLuas = (float) ($lahan->lahan_luas ?? 0);
            $totalAreaSum += $areaLuas;

            $monthData = [];
            $rowSumKg = 0;

            foreach ($months as $m) {
                $valKg = (float) ($monthlyMap[$lahan->lahan_id][$m['key']] ?? 0);
                $monthData[$m['key']] = $valKg;
                $rowSumKg += $valKg;
                $monthlyTotals[$m['key']] += $valKg;
            }

            $totalSumKg += $rowSumKg;
            $totalTon = round($rowSumKg / 1000, 2);
            $yph = $areaLuas > 0 ? round($totalTon / $areaLuas, 2) : 0;

            $rows[] = [
                'no'               => $idx + 1,
                'id_petani'        => $lahan->petani_id ?? '-',
                'id_blok'          => $lahan->lahan_nama ?? '-',
                'smallholder_name' => $lahan->petani->petani_nama ?? '-',
                'location'         => $lahan->petani->desa->desa_nama ?? $lahan->lahan_lokasi ?? '-',
                'lng_dms'          => $coords['lng_dms'],
                'lat_dms'          => $coords['lat_dms'],
                'area_total'       => $areaLuas,
                'area_production'  => $areaLuas,
                'planted_year'     => $lahan->tahun_tanam ?? '-',
                'monthly'          => $monthData,
                'total_kg'         => $rowSumKg,
                'total_ton'        => $totalTon,
                'yph'              => $yph,
            ];
        }

        $overallTon = round($totalSumKg / 1000, 2);
        $overallYph = $totalAreaSum > 0 ? round($overallTon / $totalAreaSum, 2) : 0;

        // Ambil daftar desa untuk dropdown filter
        $desas = Desa::orderBy('desa_nama', 'asc')->get();

        return compact(
            'tahun',
            'desaId',
            'search',
            'months',
            'rows',
            'totalAreaSum',
            'monthlyTotals',
            'totalSumKg',
            'overallTon',
            'overallYph',
            'desas'
        );
    }

    /**
     * Tampilan Halaman Rekap Produksi Petani (RSPO)
     */
    public function index(Request $request)
    {
        $data = $this->getProduksiData($request);
        $user = auth()->user();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.produksi.index', $data);
        } elseif ($user->user_role === 'admin') {
            return view('admin.produksi.index', $data);
        }

        abort(403);
    }

    /**
     * Ekspor File Excel Format RSPO (.xlsx)
     */
    public function export(Request $request)
    {
        $data = $this->getProduksiData($request);
        $tahun = $data['tahun'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("All Petani {$tahun}");

        // Page setup landscape
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // 1. JUDUL LAPORAN (Baris 2)
        $titleText = "Basic Info dan Produksi Asosiasi PSKS Pelalawan Siak Sertifikasi RSPO Tahun {$tahun}";
        $sheet->mergeCells('A2:X2');
        $sheet->setCellValue('A2', $titleText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 14,
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(28);

        // 2. HEADER TABEL (Baris 3 dan 4)
        $sheet->mergeCells('A3:A4')->setCellValue('A3', 'No');
        $sheet->mergeCells('B3:B4')->setCellValue('B3', 'ID Petani');
        $sheet->mergeCells('C3:C4')->setCellValue('C3', 'ID Blok');
        $sheet->mergeCells('D3:D4')->setCellValue('D3', 'Smallholder Name');
        $sheet->mergeCells('E3:E4')->setCellValue('E3', 'Location');

        $sheet->mergeCells('F3:G3')->setCellValue('F3', 'Coordinate');
        $sheet->setCellValue('F4', "Longitude (E)");
        $sheet->setCellValue('G4', "Latitude (N)");

        $sheet->mergeCells('H3:I3')->setCellValue('H3', 'Area (Ha)');
        $sheet->setCellValue('H4', 'Total');
        $sheet->setCellValue('I4', 'Production');

        $sheet->mergeCells('J3:J4')->setCellValue('J3', 'Planted Year');

        // 12 Bulan Audit Periode
        $sheet->mergeCells('K3:V3')->setCellValue('K3', $tahun);
        $monthCols = ['K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];
        foreach ($data['months'] as $i => $m) {
            $colLetter = $monthCols[$i];
            $sheet->setCellValue("{$colLetter}4", $m['label']);
        }

        $sheet->mergeCells('W3:W4')->setCellValue('W3', "Total Produksi\n(Ton)");
        $sheet->mergeCells('X3:X4')->setCellValue('X3', "YPH\n(Ton/Ha/Thn)");

        // Styling Header (Latar Abu-abu, Teks Tebal, Border)
        $headerStyle = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'BFBFBF']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A3:X4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(3)->setRowHeight(24);
        $sheet->getRowDimension(4)->setRowHeight(24);

        // 3. PENGISIAN DATA (Mulai Baris 5)
        $currentRow = 5;
        foreach ($data['rows'] as $r) {
            $sheet->setCellValue("A{$currentRow}", $r['no']);
            $sheet->setCellValueExplicit("B{$currentRow}", $r['id_petani'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$currentRow}", $r['id_blok'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("D{$currentRow}", $r['smallholder_name']);
            $sheet->setCellValue("E{$currentRow}", $r['location']);
            $sheet->setCellValue("F{$currentRow}", $r['lng_dms']);
            $sheet->setCellValue("G{$currentRow}", $r['lat_dms']);
            $sheet->setCellValue("H{$currentRow}", $r['area_total']);
            $sheet->setCellValue("I{$currentRow}", $r['area_production']);
            $sheet->setCellValueExplicit("J{$currentRow}", (string)$r['planted_year'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // 12 Bulan (Kg)
            $mIndex = 0;
            foreach ($data['months'] as $m) {
                $colLetter = $monthCols[$mIndex];
                $valKg = $r['monthly'][$m['key']] ?? 0;
                $sheet->setCellValue("{$colLetter}{$currentRow}", $valKg);
                $mIndex++;
            }

            // Total Produksi (Ton) -> Formula =SUM(K..V)/1000
            $sheet->setCellValue("W{$currentRow}", "=SUM(K{$currentRow}:V{$currentRow})/1000");

            // YPH (Ton/Ha/Thn) -> Formula =IF(I>0, W/I, 0)
            $sheet->setCellValue("X{$currentRow}", "=IF(I{$currentRow}>0, W{$currentRow}/I{$currentRow}, 0)");

            $currentRow++;
        }

        $lastDataRow = $currentRow - 1;

        // 4. BARIS TOTAL / RINGKASAN
        if ($lastDataRow >= 5) {
            $sheet->mergeCells("A{$currentRow}:G{$currentRow}")->setCellValue("A{$currentRow}", "TOTAL");
            $sheet->setCellValue("H{$currentRow}", "=SUM(H5:H{$lastDataRow})");
            $sheet->setCellValue("I{$currentRow}", "=SUM(I5:I{$lastDataRow})");
            $sheet->setCellValue("J{$currentRow}", "-");

            foreach ($monthCols as $col) {
                $sheet->setCellValue("{$col}{$currentRow}", "=SUM({$col}5:{$col}{$lastDataRow})");
            }

            $sheet->setCellValue("W{$currentRow}", "=SUM(W5:W{$lastDataRow})");
            $sheet->setCellValue("X{$currentRow}", "=IF(I{$currentRow}>0, W{$currentRow}/I{$currentRow}, 0)");

            $summaryStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EAEAEA']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => '000000']
                    ],
                    'top' => [
                        'borderStyle' => Border::BORDER_DOUBLE
                    ]
                ]
            ];
            $sheet->getStyle("A{$currentRow}:X{$currentRow}")->applyFromArray($summaryStyle);
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Styling All Data Rows
            $dataStyle = [
                'font' => [
                    'name' => 'Calibri',
                    'size' => 9.5
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'D0D0D0']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ];
            $sheet->getStyle("A5:X{$lastDataRow}")->applyFromArray($dataStyle);

            // Alignment spesifik
            $sheet->getStyle("A5:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D5:D{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E5:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J5:J{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Format Numbering
            $sheet->getStyle("H5:I{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("K5:V{$currentRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("W5:X{$currentRow}")->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Auto-width kolom
        foreach (range('A', 'X') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan output ke stream download
        $fileName = "Data_Produksi_Petani_RSPO_{$tahun}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
