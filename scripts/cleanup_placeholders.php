<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kriteria;
use App\Models\SubKriteria;

// Allowed names per kode
$allowed = [
    'C1' => ['Putih','Kuning','Hitam'],
    'C2' => ['Bersih','Lunak','Berlubang'],
    'C3' => ['Halus','Berkerut','Kasar'],
    'C4' => ['Tidak Berbau','Sedikit Berbau','Sangat Busuk'],
    'C5' => ['Normal','Mengecil','Membesar'],
];

$deleted = [];
foreach ($allowed as $kode => $names) {
    $k = Kriteria::where('kode_kriteria', $kode)->first();
    if (! $k) { echo "Kriteria $kode not found, skipping.\n"; continue; }
    $kid = $k->id;
    echo "Checking $kode (id=$kid)...\n";
    $subs = SubKriteria::where('kriteria_id', $kid)->get();
    foreach ($subs as $s) {
        if (! in_array($s->nama_sub, $names)) {
            // delete placeholder
            echo " - Found placeholder '{$s->nama_sub}' (id={$s->id})\n";
            // remove any penyakit_sub_kriterias referencing this sub_kriteria first
            $count = \App\Models\PenyakitSubKriteria::where('sub_kriteria_id', $s->id)->count();
            if ($count > 0) {
                echo "   -> Deleting {$count} mapping(s) in penyakit_sub_kriterias...\n";
                \App\Models\PenyakitSubKriteria::where('sub_kriteria_id', $s->id)->delete();
            }
            echo "   -> Deleting sub_kriteria id={$s->id}...\n";
            $deleted[] = $s->id;
            $s->delete();
        }
    }
}

echo "\nDeleted " . count($deleted) . " placeholder sub_kriteria.\n";

echo "Final sub_kriteria per kriteria:\n";
foreach (Kriteria::orderBy('id')->get() as $k) {
    echo "\n{$k->kode_kriteria} {$k->nama_kriteria}:\n";
    foreach (SubKriteria::where('kriteria_id', $k->id)->orderBy('nilai','desc')->get() as $s) {
        echo sprintf("  - %-20s %d (id=%d)\n", $s->nama_sub, $s->nilai, $s->id);
    }
}

echo "\nCleanup complete.\n";
