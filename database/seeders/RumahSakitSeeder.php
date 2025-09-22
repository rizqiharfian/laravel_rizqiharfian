<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RumahSakit;

class RumahSakitSeeder extends Seeder
{
    public function run(): void
    {
        RumahSakit::create([
            'nama_rumah_sakit' => 'RS Harapan',
            'alamat' => 'Jakarta',
            'email' => 'rs.harapan@mail.com',
            'telepon' => '021123456'
        ]);

        RumahSakit::create([
            'nama_rumah_sakit' => 'RS Sehat Selalu',
            'alamat' => 'Bandung',
            'email' => 'rs.sehat@mail.com',
            'telepon' => '022123456'
        ]);
    }
}

