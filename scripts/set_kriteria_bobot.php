<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kriteria;

$desired = [
    'C1' => ['nama_kriteria' => 'Warna Tutup', 'bobot' => 0.20, 'jenis' => 'benefit'],
    'C2' => ['nama_kriteria' => 'Kondisi Bekas Luka', 'bobot' => 0.25, 'jenis' => 'cost'],
    'C3' => ['nama_kriteria' => 'Tekstur', 'bobot' => 0.15, 'jenis' => 'benefit'],
    'C4' => ['nama_kriteria' => 'Bau', 'bobot' => 0.25, 'jenis' => 'cost'],
    'C5' => ['nama_kriteria' => 'Perubahan Ukuran', 'bobot' => 0.15, 'jenis' => 'benefit'],
];

echo "Updating kriteria bobot...\n";
foreach ($desired as $code => $vals) {
    $k = Kriteria::where('kode_kriteria', $code)->first();
    if ($k) {
        $k->nama_kriteria = $vals['nama_kriteria'];
        $k->bobot = $vals['bobot'];
        $k->jenis = $vals['jenis'];
        $k->save();
        echo "Updated {$code}: {$vals['nama_kriteria']} (bobot={$vals['bobot']})\n";
    } else {
        Kriteria::create([
            'kode_kriteria' => $code,
            'nama_kriteria' => $vals['nama_kriteria'],
            'bobot' => $vals['bobot'],
            'jenis' => $vals['jenis'],
        ]);
        echo "Created {$code}: {$vals['nama_kriteria']} (bobot={$vals['bobot']})\n";
    }
}

// show results
echo "\nCurrent kriteria:\n";
foreach (Kriteria::orderBy('id')->get() as $k) {
    printf("%s | %s | %.2f | %s\n", $k->kode_kriteria, $k->nama_kriteria, $k->bobot, $k->jenis);
}

$sum = Kriteria::sum('bobot');
echo "\nTotal bobot sum = " . number_format($sum, 2) . "\n";

echo "Done.\n";
