<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penyakit;

class PenyakitSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode_penyakit'=>'P01','nama_penyakit'=>'Bacterial Blotch','deskripsi'=>'Infeksi bakteri pada tutup jamur','solusi'=>'Hentikan penyiraman dan sanitasi'],
            ['kode_penyakit'=>'P02','nama_penyakit'=>'Cobweb Mold','deskripsi'=>'Jamur layu dan penutup berwarna abu','solusi'=>'Tingkatkan sirkulasi udara'],
            ['kode_penyakit'=>'P03','nama_penyakit'=>'Dry Bubble','deskripsi'=>'Benjolan kering dan deformasi','solusi'=>'Buang bagian terserang dan perbaiki kelembapan'],
        ];
        foreach ($items as $it) Penyakit::create($it);
    }
}
