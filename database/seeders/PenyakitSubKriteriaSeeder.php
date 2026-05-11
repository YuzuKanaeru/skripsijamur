<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenyakitSubKriteria;

class PenyakitSubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // For P01 (Bacterial Blotch) mapping (use sub_kriteria ids from SubKriteriaSeeder)
        PenyakitSubKriteria::create(['penyakit_id'=>1,'kriteria_id'=>1,'sub_kriteria_id'=>2]);
        PenyakitSubKriteria::create(['penyakit_id'=>1,'kriteria_id'=>2,'sub_kriteria_id'=>3]);
        PenyakitSubKriteria::create(['penyakit_id'=>1,'kriteria_id'=>3,'sub_kriteria_id'=>6]);
        PenyakitSubKriteria::create(['penyakit_id'=>1,'kriteria_id'=>4,'sub_kriteria_id'=>9]);
        PenyakitSubKriteria::create(['penyakit_id'=>1,'kriteria_id'=>5,'sub_kriteria_id'=>11]);

        // P02 (Cobweb Mold)
        PenyakitSubKriteria::create(['penyakit_id'=>2,'kriteria_id'=>1,'sub_kriteria_id'=>3]);
        PenyakitSubKriteria::create(['penyakit_id'=>2,'kriteria_id'=>2,'sub_kriteria_id'=>2]);
        PenyakitSubKriteria::create(['penyakit_id'=>2,'kriteria_id'=>3,'sub_kriteria_id'=>7]);
        PenyakitSubKriteria::create(['penyakit_id'=>2,'kriteria_id'=>4,'sub_kriteria_id'=>10]);
        PenyakitSubKriteria::create(['penyakit_id'=>2,'kriteria_id'=>5,'sub_kriteria_id'=>12]);

        // P03 (Dry Bubble)
        PenyakitSubKriteria::create(['penyakit_id'=>3,'kriteria_id'=>1,'sub_kriteria_id'=>1]);
        PenyakitSubKriteria::create(['penyakit_id'=>3,'kriteria_id'=>2,'sub_kriteria_id'=>1]);
        PenyakitSubKriteria::create(['penyakit_id'=>3,'kriteria_id'=>3,'sub_kriteria_id'=>6]);
        PenyakitSubKriteria::create(['penyakit_id'=>3,'kriteria_id'=>4,'sub_kriteria_id'=>10]);
        PenyakitSubKriteria::create(['penyakit_id'=>3,'kriteria_id'=>5,'sub_kriteria_id'=>11]);
    }
}
