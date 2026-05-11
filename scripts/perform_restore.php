<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Create backup via DB tables export using native SELECT INTO OUTFILE is not safe here,
// so try to use mysqldump if present.
$backupDir = $projectRoot . '/storage/backup';
if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);
$ts = date('Ymd_His');
$dumpFile = $backupDir . "/spkjamur-backup-{$ts}.sql";
$mysqldump = 'C:/xampp/mysql/bin/mysqldump.exe';
$host = env('DB_HOST','127.0.0.1');
$port = env('DB_PORT','3306');
$db = env('DB_DATABASE');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');
$dumped = false;
if (file_exists($mysqldump)) {
    $cmd = sprintf('"%s" -h %s -P %s -u %s %s', $mysqldump, $host, $port, $user, $db);
    if ($pass !== null && $pass !== '') {
        // write a temporary my.cnf is insecure; opt to include -p but no password (will prompt) — skip password if empty
        $cmd = sprintf('"%s" -h %s -P %s -u %s -p%s %s', $mysqldump, $host, $port, $user, $pass, $db);
    }
    $full = $cmd . ' > "' . $dumpFile . '"';
    echo "Running: $full\n";
    system($full, $ret);
    if ($ret === 0) {
        echo "Database dumped to $dumpFile\n";
        $dumped = true;
    } else {
        echo "mysqldump returned code $ret. Dump may have failed.\n";
    }
} else {
    echo "mysqldump not found at $mysqldump — skipping binary dump.\n";
}

// If no binary dump, do a lightweight CSV export of core tables as a minimal backup
if (! $dumped) {
    $tables = ['users','kriterias','sub_kriterias','penyakits','penyakit_sub_kriterias','data_jamurs','detail_data_jamurs','hasil_saws'];
    foreach ($tables as $t) {
        $rows = DB::table($t)->get();
        file_put_contents($backupDir . "/{$t}-{$ts}.json", json_encode($rows, JSON_PRETTY_PRINT));
        echo "Wrote backup JSON for $t\n";
    }
}

// Now truncate core tables safely with FK checks off
echo "Disabling foreign key checks and truncating tables...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');
$truncate = ['detail_data_jamurs','hasil_saws','data_jamurs','penyakit_sub_kriterias','sub_kriterias','penyakits','kriterias','users'];
foreach ($truncate as $t) {
    try{
        DB::table($t)->truncate();
        echo "Truncated $t\n";
    }catch(Exception $e){ echo "Failed to truncate $t: ".$e->getMessage()."\n"; }
}
DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Run seeder
echo "Running DatabaseSeeder...\n";
Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
echo Artisan::output();

echo "Restore complete. Backup file: $dumpFile (if created)\n";
