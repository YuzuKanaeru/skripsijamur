<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SubKriteria;

$sub = SubKriteria::first();
if (! $sub) { echo "No subkriteria found\n"; exit; }

echo route('admin.sub-kriteria.update', $sub) . PHP_EOL;
