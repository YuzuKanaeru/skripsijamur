<?php
// Debug script to print decision matrix, max per criterion, classical normalization, and similarity normalization
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataJamur;
use App\Models\Kriteria;
use App\Models\Penyakit;

$id = $argv[1] ?? null;
if ($id) {
    $dj = DataJamur::find($id);
    if (!$dj) {
        echo "DataJamur id $id not found\n";
        exit(1);
    }
} else {
    $dj = DataJamur::orderBy('id','desc')->first();
    if (!$dj) {
        echo "No DataJamur records found\n";
        exit(1);
    }
}

echo "Using DataJamur ID: {$dj->id}, tanggal: {$dj->tanggal}\n";

$kriterias = Kriteria::orderBy('id')->get();
$penyakits = Penyakit::all();

echo "\nKriteria (id, nama_kriteria, jenis, bobot)\n";
foreach ($kriterias as $k) {
    printf("%3d %-30s %-10s %8s\n", $k->id, $k->nama_kriteria, $k->jenis, $k->bobot);
}

// build x matrix
$x = [];
foreach ($penyakits as $p) {
    foreach ($kriterias as $k) {
        $mapping = $p->subKriteria()->where('kriteria_id', $k->id)->first();
        $val = 0;
        if ($mapping) $val = $mapping->subKriteria?->nilai ?? 0;
        $x[$p->id][$k->id] = $val;
    }
}

// compute max per criterion
$max = [];
foreach ($kriterias as $k) {
    $col = [];
    foreach ($penyakits as $p) {
        $col[] = $x[$p->id][$k->id] ?? 0;
    }
    $max[$k->id] = count($col) ? max($col) : 0;
}

// user inputs
$dj->load('detailDataJamur');
$userInputs = [];
foreach ($dj->detailDataJamur as $d) {
    $userInputs[$d->kriteria_id] = $d->nilai;
}

echo "\nMatriks Awal (x_ij)\n";
// header
printf("%-30s", 'Penyakit');
foreach ($kriterias as $k) printf("%8s", $k->nama_kriteria);
echo "\n";
foreach ($penyakits as $p) {
    printf("%-30s", $p->nama_penyakit);
    foreach ($kriterias as $k) {
        printf("%8s", $x[$p->id][$k->id] ?? 0);
    }
    echo "\n";
}

echo "\nMax per Kriteria\n";
foreach ($kriterias as $k) printf("%8s", $max[$k->id]);
echo "\n";

// classical normalization
echo "\nNormalisasi Klasik (rij)\n";
printf("%-30s", 'Penyakit');
foreach ($kriterias as $k) printf("%8s", $k->nama_kriteria);
echo "\n";
foreach ($penyakits as $p) {
    printf("%-30s", $p->nama_penyakit);
    foreach ($kriterias as $k) {
        $xij = $x[$p->id][$k->id] ?? 0;
        if ($k->jenis === 'benefit') {
            $rij = $max[$k->id] > 0 ? ($xij / $max[$k->id]) : 0;
        } else {
            $min = null;
            // compute min
            $col = [];
            foreach ($penyakits as $pp) $col[] = $x[$pp->id][$k->id] ?? 0;
            $min = count($col) ? min($col) : 0;
            $rij = $xij > 0 ? ($min / $xij) : 0;
        }
        printf("%8.4f", $rij);
    }
    echo "\n";
}

// similarity normalization (using user inputs if present)
$maxScale = 5.0;
echo "\nNormalisasi Similarity (rij_sim)\n";
printf("%-30s", 'Penyakit');
foreach ($kriterias as $k) printf("%8s", $k->nama_kriteria);
echo "\n";
foreach ($penyakits as $p) {
    printf("%-30s", $p->nama_penyakit);
    foreach ($kriterias as $k) {
        $xij = $x[$p->id][$k->id] ?? 0;
        if (isset($userInputs[$k->id])) {
            $inputVal = floatval($userInputs[$k->id]);
            $diff = abs($inputVal - $xij);
            $rij_sim = 1 - ($diff / $maxScale);
            if ($rij_sim < 0) $rij_sim = 0;
        } else {
            if ($k->jenis === 'benefit') {
                $rij_sim = $max[$k->id] > 0 ? ($xij / $max[$k->id]) : 0;
            } else {
                $min = null;
                $col = [];
                foreach ($penyakits as $pp) $col[] = $x[$pp->id][$k->id] ?? 0;
                $min = count($col) ? min($col) : 0;
                $rij_sim = $xij > 0 ? ($min / $xij) : 0;
            }
        }
        printf("%8.4f", $rij_sim);
    }
    echo "\n";
}

echo "\nDone\n";
