<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

echo "Running DatabaseSeeder to restore core seed data...\n";
Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
echo Artisan::output();

// remove temporary user and its data_jamurs
$tmpEmail = 'tmp+matrix@example.com';
$u = App\Models\User::where('email', $tmpEmail)->first();
if ($u) {
    echo "Found tmp user id={$u->id}, removing its data_jamurs and then user...\n";
    App\Models\DataJamur::where('user_id', $u->id)->delete();
    $u->delete();
} else {
    echo "No tmp user found.\n";
}

// remove TMP_ penyakit entries
$tmps = App\Models\Penyakit::where('kode_penyakit','like','TMP_%')->get();
if ($tmps->count()) {
    echo "Removing TMP_ penyakit entries (count={$tmps->count()})...\n";
    foreach ($tmps as $p) {
        App\Models\PenyakitSubKriteria::where('penyakit_id',$p->id)->delete();
        $p->delete();
    }
} else {
    echo "No TMP_ penyakit found.\n";
}

echo "Cleanup done.\n";
