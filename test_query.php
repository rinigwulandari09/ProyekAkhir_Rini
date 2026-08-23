<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing Pemasukan Query...\n";
    $pemasukan = DB::table('detail_produksi')
        ->join('produksi', 'detail_produksi.produksi_id', '=', 'produksi.id')
        ->join('lahan', 'detail_produksi.lahan_id', '=', 'lahan.lahan_id')
        ->select(
            'produksi.id as id',
            'detail_produksi.detail_produksi_id as detail_id',
            'produksi.produksi_tanggal as tanggal',
            'detail_produksi.subtotal_pendapatan as nominal',
            'detail_produksi.jumlah_tbs as jumlah_tbs',
            'detail_produksi.lahan_id as lahan_id',
            'lahan.lahan_nama as lahan_nama',
            DB::raw("'pemasukan' as tipe"),
            DB::raw("'Penjualan TBS' as judul"),
            DB::raw("'produksi' as source_table")
        )->get();
    echo "Pemasukan count: " . count($pemasukan) . "\n";

    echo "Testing Pengeluaran Query...\n";
    $pengeluaran = DB::table('biaya_operasional')
        ->leftJoin('detail_biaya_operasional', 'biaya_operasional.id', '=', 'detail_biaya_operasional.biaya_operasional_id')
        ->leftJoin('lahan', 'detail_biaya_operasional.lahan_id', '=', 'lahan.lahan_id')
        ->select(
            'biaya_operasional.id as id',
            'detail_biaya_operasional.detail_biaya_operasional_id as detail_id',
            'biaya_operasional.biaya_tanggal as tanggal',
            'biaya_operasional.biaya_total as nominal',
            'biaya_operasional.biaya_jumlah as jumlah_tbs',
            'biaya_operasional.biaya_nama as judul',
            'detail_biaya_operasional.lahan_id as lahan_id',
            DB::raw("COALESCE(lahan.lahan_nama, '') as lahan_nama"),
            DB::raw("'pengeluaran' as tipe"),
            DB::raw("'biaya' as source_table")
        )->get();
    echo "Pengeluaran count: " . count($pengeluaran) . "\n";

} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
}
