<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(App\Models\Kriteria::orderBy('id')->get() as $r){ echo $r->id.' | '.$r->kode_kriteria.' | '.$r->nama_kriteria.PHP_EOL; }
