<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan firstOrCreate agar jika akun sudah ada (misal di DB saat ini),
        // password dan data yang ada tidak tertimpa/berubah.
        User::firstOrCreate(
            ['user_username' => 'rini'],
            [
                'user_nama'     => 'Rini Wulandari',
                'user_email'    => 'rini23ti@mahasiswa.pcr.ac.id',
                'user_password' => Hash::make('123456'), // Password default, dapat diubah sesuai kebutuhan
                'user_role'     => 'super_admin',
                'desa_id'       => 3,
            ]
        );
    }
}
