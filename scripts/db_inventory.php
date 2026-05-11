<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
$tables=['users','kriterias','sub_kriterias','penyakits','penyakit_sub_kriterias','data_jamurs','detail_data_jamurs','hasil_saws'];
foreach($tables as $t){
    try{ $c = DB::table($t)->count(); }catch(Exception $e){ $c = 'ERR'; }
    echo $t.': '.$c.PHP_EOL;
}

// show last 10 users and last 10 data_jamurs
echo "\nLast users:\n";
foreach(App\Models\User::orderBy('id','desc')->limit(10)->get() as $u){ echo $u->id.' '.$u->email.' '.($u->role??'').'\n'; }

echo "\nLast data_jamurs:\n";
foreach(App\Models\DataJamur::orderBy('id','desc')->limit(10)->get() as $d){ echo $d->id.' '.$d->tanggal.' user_id='.($d->user_id??'')."\n"; }
