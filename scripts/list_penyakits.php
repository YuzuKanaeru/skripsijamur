<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(App\Models\Penyakit::orderBy('id')->get() as $p){ echo $p->id.' | '.$p->kode_penyakit.' | '.$p->nama_penyakit.PHP_EOL; }
