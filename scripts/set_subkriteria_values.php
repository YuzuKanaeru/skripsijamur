<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kriteria;
use App\Models\SubKriteria;

// desired mapping: kode_kriteria => [ [nama_sub, nilai], ... ]
$map = [
    'C1' => [['Putih',5], ['Kuning',3], ['Hitam',1]],
    'C2' => [['Bersih',5], ['Lunak',3], ['Berlubang',1]],
    'C3' => [['Halus',5], ['Berkerut',3], ['Kasar',1]],
    'C4' => [['Tidak Berbau',5], ['Sedikit Berbau',3], ['Sangat Busuk',1]],
    'C5' => [['Normal',5], ['Mengecil',3], ['Membesar',1]],
];

foreach ($map as $kode => $subs) {
    $k = Kriteria::where('kode_kriteria', $kode)->first();
    if (! $k) {
        echo "Kriteria $kode tidak ditemukan, melewatkan...\n";
        continue;
    }
    $kid = $k->id;
    echo "\nProcessing $kode (id=$kid) - {$k->nama_kriteria}\n";
    foreach ($subs as $pair) {
        [$name, $val] = $pair;
        // try to find existing by exact name and kriteria_id
        $sub = SubKriteria::where('kriteria_id', $kid)->where('nama_sub', $name)->first();
        if ($sub) {
            $old = $sub->nilai;
            $sub->nilai = $val;
            $sub->save();
            echo "Updated '{$name}' (id={$sub->id}) from {$old} -> {$val}\n";
        } else {
            // try case-insensitive match
            $sub2 = SubKriteria::where('kriteria_id', $kid)->whereRaw('LOWER(nama_sub)=?', [strtolower($name)])->first();
            if ($sub2) {
                $old = $sub2->nilai;
                $sub2->nilai = $val;
                $sub2->save();
                echo "Updated (ci) '{$sub2->nama_sub}' (id={$sub2->id}) from {$old} -> {$val}\n";
            } else {
                // create new
                $created = SubKriteria::create(['kriteria_id' => $kid, 'nama_sub' => $name, 'nilai' => $val]);
                echo "Created '{$name}' (id={$created->id}) = {$val}\n";
            }
        }
    }
}

// show current subkriteria grouped
echo "\nFinal sub_kriteria list:\n";
$kriterias = Kriteria::orderBy('id')->get();
foreach ($kriterias as $k) {
    echo "\n{$k->kode_kriteria} {$k->nama_kriteria}:\n";
    foreach (SubKriteria::where('kriteria_id', $k->id)->orderBy('nilai','desc')->get() as $s) {
        echo sprintf("  - %-20s %d (id=%d)\n", $s->nama_sub, $s->nilai, $s->id);
    }
}

echo "\nDone.\n";
