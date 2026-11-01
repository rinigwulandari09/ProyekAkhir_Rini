<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Master Jenis Kegiatan
        $jenisKegiatan = [
            ['id_jenis' => 1, 'nama_jenis' => 'Panen TBS', 'ikon' => 'harvest'],
            ['id_jenis' => 2, 'nama_jenis' => 'Pemupukan', 'ikon' => 'fertilizer'],
            ['id_jenis' => 3, 'nama_jenis' => 'Penyemprotan Gulma', 'ikon' => 'spray'],
            ['id_jenis' => 4, 'nama_jenis' => 'Penunasan', 'ikon' => 'pruning'],
            ['id_jenis' => 5, 'nama_jenis' => 'Pengendalian Hama', 'ikon' => 'bug'],
            ['id_jenis' => 6, 'nama_jenis' => 'Pembersihan Kebun', 'ikon' => 'clean'],
            ['id_jenis' => 7, 'nama_jenis' => 'Pengangkutan Hasil', 'ikon' => 'truck'],
        ];

        foreach ($jenisKegiatan as $jk) {
            DB::table('jenis_kegiatan')->updateOrInsert(
                ['id_jenis' => $jk['id_jenis']],
                ['nama_jenis' => $jk['nama_jenis'], 'ikon' => $jk['ikon']]
            );
        }

        // Master Desa
        $desa = [
            ['desa_id' => 1, 'desa_nama' => 'Maredan'],
            ['desa_id' => 2, 'desa_nama' => 'Simpang Beringin'],
            ['desa_id' => 3, 'desa_nama' => 'Sekijang'],
            ['desa_id' => 4, 'desa_nama' => 'Lubuk Ogung'],
            ['desa_id' => 5, 'desa_nama' => 'Kerinci Kiri'],
            ['desa_id' => 6, 'desa_nama' => 'Kiab Jaya 1'],
            ['desa_id' => 7, 'desa_nama' => 'Kiab Jaya 2'],
            ['desa_id' => 8, 'desa_nama' => 'Langgam'],
            ['desa_id' => 9, 'desa_nama' => 'Muda Setia'],
            ['desa_id' => 10, 'desa_nama' => 'Beringin Indah'],
            ['desa_id' => 11, 'desa_nama' => 'Dundangan'],
            ['desa_id' => 12, 'desa_nama' => 'Sialang Indah'],
            ['desa_id' => 19, 'desa_nama' => 'Kiab Jaya'],
        ];

        foreach ($desa as $d) {
            DB::table('desa')->updateOrInsert(
                ['desa_id' => $d['desa_id']],
                ['desa_nama' => $d['desa_nama']]
            );
        }
    }
}
