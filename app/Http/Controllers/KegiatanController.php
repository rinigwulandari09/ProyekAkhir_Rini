<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\JenisKegiatan;
use App\Models\DetailKegiatan;
use App\Models\Petani;
use App\Models\Desa;
use App\Models\Lahan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KegiatanController extends Controller
{
    /**
     * Menampilkan daftar riwayat kegiatan petani (Inputan Aplikasi Mobile)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Parameter filter
        $desaId = $request->input('desa_id');
        $jenisId = $request->input('jenis_id');
        $statusLimbah = $request->input('status_limbah');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $search = $request->input('search');

        // Query Utama Kegiatan
        $query = Kegiatan::with([
            'petani.desa',
            'jenis',
            'detailLahan.lahan'
        ]);

        // Filter role admin desa
        if ($user->user_role === 'admin' && $user->desa_id) {
            $query->whereHas('petani', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        } elseif ($desaId) {
            $query->whereHas('petani', function ($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
        }

        // Filter jenis kegiatan
        if ($jenisId) {
            $query->where('jenis_kegiatan_id', $jenisId);
        }

        // Filter status limbah
        if ($statusLimbah) {
            $query->where('status_limbah', $statusLimbah);
        }

        // Filter bulan & tahun
        if ($bulan) {
            $query->whereMonth('kegiatan_tanggal', $bulan);
        }
        if ($tahun) {
            $query->whereYear('kegiatan_tanggal', $tahun);
        }

        // Filter search keyword
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'ilike', "%{$search}%")
                  ->orWhere('nama_bahan', 'ilike', "%{$search}%")
                  ->orWhere('kegiatan_ket', 'ilike', "%{$search}%")
                  ->orWhereHas('petani', function ($qp) use ($search) {
                      $qp->where('petani_nama', 'ilike', "%{$search}%");
                  });
            });
        }

        $kegiatans = $query->orderByDesc('kegiatan_tanggal')
                           ->orderByDesc('kegiatan_id')
                           ->get();

        // Rekapitulasi Statistik Ringkas
        $statQuery = Kegiatan::query();
        if ($user->user_role === 'admin' && $user->desa_id) {
            $statQuery->whereHas('petani', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        }

        $totalKegiatan = (clone $statQuery)->count();
        $kegiatanBulanIni = (clone $statQuery)
            ->whereMonth('kegiatan_tanggal', date('m'))
            ->whereYear('kegiatan_tanggal', date('Y'))
            ->count();

        $limbahBelumDisetor = (clone $statQuery)
            ->where('status_limbah', 'Belum Disetor')
            ->count();

        // Ambil jenis kegiatan paling sering dilakukan
        $jenisTerbanyakRow = (clone $statQuery)
            ->select('jenis_kegiatan_id', DB::raw('count(*) as total'))
            ->groupBy('jenis_kegiatan_id')
            ->orderByDesc('total')
            ->first();

        $jenisTerbanyak = '-';
        if ($jenisTerbanyakRow && $jenisTerbanyakRow->jenis_kegiatan_id) {
            $jenisObj = JenisKegiatan::find($jenisTerbanyakRow->jenis_kegiatan_id);
            $jenisTerbanyak = $jenisObj ? $jenisObj->nama_jenis : '-';
        }

        // Dropdown data pendukung filter
        $desas = Desa::orderBy('desa_nama', 'asc')->get();
        $jenisList = JenisKegiatan::orderBy('nama_jenis', 'asc')->get();
        $tahunList = Kegiatan::selectRaw('EXTRACT(YEAR FROM kegiatan_tanggal)::int as tahun')
                             ->distinct()
                             ->orderByDesc('tahun')
                             ->pluck('tahun')
                             ->toArray();
        if (empty($tahunList)) {
            $tahunList = [(int) date('Y')];
        }

        $viewData = compact(
            'kegiatans',
            'totalKegiatan',
            'kegiatanBulanIni',
            'limbahBelumDisetor',
            'jenisTerbanyak',
            'desas',
            'jenisList',
            'tahunList',
            'desaId',
            'jenisId',
            'statusLimbah',
            'bulan',
            'tahun',
            'search'
        );

        if ($user->user_role === 'super_admin') {
            return view('super_admin.kegiatan.index', $viewData);
        } elseif ($user->user_role === 'admin') {
            return view('admin.kegiatan.index', $viewData);
        }

        abort(403);
    }

    /**
     * Mengambil detail satu kegiatan (JSON untuk modal preview)
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::with([
            'petani.desa',
            'jenis',
            'detailLahan.lahan'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $kegiatan
        ]);
    }

    /**
     * Memperbarui status limbah (misal: dari 'Belum Disetor' -> 'Sudah Disetor')
     */
    public function updateStatusLimbah(Request $request, $id)
    {
        $request->validate([
            'status_limbah' => 'required|in:Belum Disetor,Sudah Disetor'
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status_limbah = $request->status_limbah;
        $kegiatan->save();

        return redirect()->back()->with('success', 'Status limbah kegiatan berhasil diperbarui.');
    }

    /**
     * Menghapus catatan data kegiatan
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            DetailKegiatan::where('kegiatan_id', $id)->delete();
            Kegiatan::where('kegiatan_id', $id)->delete();
        });

        return redirect()->back()->with('success', 'Data kegiatan berhasil dihapus.');
    }

    /**
     * Ekspor Data Kegiatan ke File Excel
     */
    public function export(Request $request)
    {
        $user = auth()->user();

        $query = Kegiatan::with(['petani.desa', 'jenis', 'detailLahan.lahan']);

        if ($user->user_role === 'admin' && $user->desa_id) {
            $query->whereHas('petani', function ($q) use ($user) {
                $q->where('desa_id', $user->desa_id);
            });
        } elseif ($request->filled('desa_id')) {
            $query->whereHas('petani', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->filled('jenis_id')) {
            $query->where('jenis_kegiatan_id', $request->jenis_id);
        }

        if ($request->filled('status_limbah')) {
            $query->where('status_limbah', $request->status_limbah);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('kegiatan_tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('kegiatan_tanggal', $request->tahun);
        }

        $data = $query->orderByDesc('kegiatan_tanggal')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Kegiatan Petani');

        // Header Dokumen
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'REKAPITULASI DATA KEGIATAN PERKEBUNAN PETANI (APLIKASI MOBILE)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'Asosiasi Petani Sawit - Tanggal Ekspor: ' . date('d F Y'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Kolom Tabel
        $headers = [
            'No',
            'Tanggal',
            'Nama Petani',
            'Desa',
            'Jenis Kegiatan',
            'Jumlah / Dosis',
            'Satuan',
            'Bahan / Keterangan',
            'Status Limbah B3'
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getStyle($col . '4')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('234323');
            $sheet->getStyle($col . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // Isi Data
        $rowIdx = 5;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValue('B' . $rowIdx, $item->kegiatan_tanggal ? date('d/m/Y', strtotime($item->kegiatan_tanggal)) : '-');
            $sheet->setCellValue('C' . $rowIdx, $item->petani->petani_nama ?? '-');
            $sheet->setCellValue('D' . $rowIdx, $item->petani->desa->desa_nama ?? '-');
            $sheet->setCellValue('E' . $rowIdx, $item->jenis->nama_jenis ?? ($item->nama_kegiatan ?? '-'));
            $sheet->setCellValue('F' . $rowIdx, (float) $item->kegiatan_jumlah);
            $sheet->setCellValue('G' . $rowIdx, $item->kegiatan_satuan ?? '-');
            $sheet->setCellValue('H' . $rowIdx, $item->nama_bahan ?? ($item->kegiatan_ket ?? '-'));
            $sheet->setCellValue('I' . $rowIdx, $item->status_limbah ?? 'Belum Disetor');

            // Zebra striping
            if ($rowIdx % 2 == 0) {
                $sheet->getStyle("A{$rowIdx}:I{$rowIdx}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
            }
            $rowIdx++;
        }

        // Styling Border
        $lastRow = $rowIdx - 1;
        if ($lastRow >= 4) {
            $sheet->getStyle("A4:I{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');
        }

        // Auto width
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data_Kegiatan_Petani_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

