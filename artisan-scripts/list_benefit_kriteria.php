<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('kriterias')->where('jenis','benefit')->get();
foreach ($rows as $r) {
    echo "{$r->id} - {$r->nama_kriteria} - {$r->jenis}\n";
}
