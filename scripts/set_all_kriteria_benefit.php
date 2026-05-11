<?php
// Script to set all kriteria.jenis = 'benefit'
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kriteria;

$count = Kriteria::count();
if ($count === 0) {
    echo "No kriteria records found.\n";
    exit(1);
}

Kriteria::query()->update(['jenis' => 'benefit']);

echo "Updated $count kriteria records to jenis='benefit'.\n\nCurrent list:\n";
foreach (Kriteria::orderBy('id')->get() as $k) {
    printf("%3d %-30s %-10s %8s\n", $k->id, $k->nama_kriteria, $k->jenis, $k->bobot);
}
