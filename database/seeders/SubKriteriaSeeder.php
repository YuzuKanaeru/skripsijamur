<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // For C1 (Warna Tutup)
        SubKriteria::create(['kriteria_id'=>1,'nama_sub'=>'Putih','nilai'=>5]);
        SubKriteria::create(['kriteria_id'=>1,'nama_sub'=>'Kuning','nilai'=>3]);
        SubKriteria::create(['kriteria_id'=>1,'nama_sub'=>'Hitam','nilai'=>1]);

        // C2 (Kondisi Bekas Luka) - cost
        SubKriteria::create(['kriteria_id'=>2,'nama_sub'=>'Bersih','nilai'=>5]);
        SubKriteria::create(['kriteria_id'=>2,'nama_sub'=>'Lunak','nilai'=>3]);
        SubKriteria::create(['kriteria_id'=>2,'nama_sub'=>'Berlubang','nilai'=>1]);

        // C3 (Tekstur)
        SubKriteria::create(['kriteria_id'=>3,'nama_sub'=>'Halus','nilai'=>5]);
        SubKriteria::create(['kriteria_id'=>3,'nama_sub'=>'Berkerut','nilai'=>3]);
        SubKriteria::create(['kriteria_id'=>3,'nama_sub'=>'Kasar','nilai'=>1]);

        // C4 (Bau) - cost
        SubKriteria::create(['kriteria_id'=>4,'nama_sub'=>'Tidak Berbau','nilai'=>5]);
        SubKriteria::create(['kriteria_id'=>4,'nama_sub'=>'Sedikit Berbau','nilai'=>3]);
        SubKriteria::create(['kriteria_id'=>4,'nama_sub'=>'Sangat Busuk','nilai'=>1]);

        // C5 (Perubahan Ukuran)
        SubKriteria::create(['kriteria_id'=>5,'nama_sub'=>'Normal','nilai'=>5]);
        SubKriteria::create(['kriteria_id'=>5,'nama_sub'=>'Mengecil','nilai'=>3]);
        SubKriteria::create(['kriteria_id'=>5,'nama_sub'=>'Membesar','nilai'=>1]);
    }
}
