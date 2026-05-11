<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode_kriteria'=>'C1','nama_kriteria'=>'Warna Tutup','bobot'=>0.25,'jenis'=>'benefit'],
            ['kode_kriteria'=>'C2','nama_kriteria'=>'Kondisi Bekas Luka','bobot'=>0.20,'jenis'=>'cost'],
            ['kode_kriteria'=>'C3','nama_kriteria'=>'Tekstur','bobot'=>0.20,'jenis'=>'benefit'],
            ['kode_kriteria'=>'C4','nama_kriteria'=>'Bau','bobot'=>0.15,'jenis'=>'cost'],
            ['kode_kriteria'=>'C5','nama_kriteria'=>'Perubahan Ukuran','bobot'=>0.20,'jenis'=>'benefit'],
        ];
        foreach ($items as $it) Kriteria::create($it);
    }
}
