<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kriteria;

$desired = [
    'C1' => 0.30,
    'C2' => 0.15,
    'C3' => 0.20,
    'C4' => 0.25,
    'C5' => 0.10,
];

echo "Deduplicating kriteria by kode_kriteria...\n";
foreach ($desired as $code => $targetBobot) {
    $rows = Kriteria::where('kode_kriteria', $code)->orderBy('id')->get();
    if ($rows->count() <= 1) continue;
    echo "Found {$rows->count()} rows for $code, resolving...\n";
    // Try to keep row with exact target bobot if exists
    $keep = $rows->first();
    foreach ($rows as $r) {
        if (abs($r->bobot - $targetBobot) < 0.0001) { $keep = $r; break; }
    }
    echo "Keeping id={$keep->id} (bobot={$keep->bobot})\n";
    foreach ($rows as $r) {
        if ($r->id == $keep->id) continue;
        echo "Deleting id={$r->id} (bobot={$r->bobot})\n";
        $r->delete();
    }
}

// show final kriteria and sum
echo "\nFinal kriteria:\n";
foreach (Kriteria::orderBy('id')->get() as $k) {
    printf("%d | %s | %s | %.2f\n", $k->id, $k->kode_kriteria, $k->nama_kriteria, $k->bobot);
}
$sum = Kriteria::sum('bobot');
echo "\nTotal bobot sum = " . number_format($sum, 2) . "\n";

echo "Deduplication complete.\n";
