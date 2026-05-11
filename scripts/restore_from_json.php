<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$backupDir = $projectRoot . '/storage/backup';
if (!is_dir($backupDir)) {
    echo "Backup directory not found: $backupDir\n";
    exit(1);
}

function latestBackupFile($dir, $prefix) {
    $files = glob($dir . '/' . $prefix . '-*.json');
    if (!$files) return null;
    usort($files, function($a,$b){ return filemtime($b) - filemtime($a); });
    return $files[0];
}

$mapping = [
    'users' => 'users',
    'kriterias' => 'kriterias',
    'sub_kriterias' => 'sub_kriterias',
    'penyakits' => 'penyakits',
    'penyakit_sub_kriterias' => 'penyakit_sub_kriterias',
    'data_jamurs' => 'data_jamurs',
    'detail_data_jamurs' => 'detail_data_jamurs',
    'hasil_saws' => 'hasil_saws',
];

// find files
$filesToRestore = [];
foreach ($mapping as $table => $prefix) {
    $f = latestBackupFile($backupDir, $prefix);
    if ($f) $filesToRestore[$table] = $f;
}

if (count($filesToRestore) === 0) {
    echo "No backup JSON files found in $backupDir\n";
    exit(1);
}

// order of restoration
$order = ['users','kriterias','sub_kriterias','penyakits','penyakit_sub_kriterias','data_jamurs','detail_data_jamurs','hasil_saws'];

echo "Starting JSON restore (this will overwrite current tables)\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// truncate target tables first to avoid conflicts
foreach ($order as $t) {
    if (DB::getSchemaBuilder()->hasTable($t)) {
        echo "Truncating table $t...\n";
        DB::table($t)->truncate();
    } else {
        echo "Table $t does not exist, skipping...\n";
    }
}

// insert data preserving IDs
foreach ($order as $t) {
    if (!isset($filesToRestore[$t])) {
        echo "No backup file for $t, skipping insertion.\n";
        continue;
    }
    $path = $filesToRestore[$t];
    echo "Restoring table $t from $path...\n";
    $json = file_get_contents($path);
    $rows = json_decode($json, true);
    if (!is_array($rows)) { echo "Failed to decode $path\n"; continue; }

    // Insert rows in chunks
    $maxId = 0;
    foreach (array_chunk($rows, 50) as $chunk) {
        foreach ($chunk as &$r) {
            // ensure no null values conflict with strict mode by leaving as-is
            // for boolean or json columns, trust the backup
        }
        DB::table($t)->insert($chunk);
        foreach ($chunk as $r) { if (isset($r['id']) && $r['id'] > $maxId) $maxId = $r['id']; }
    }
    // reset auto-increment
    if ($maxId > 0) {
        DB::statement("ALTER TABLE `{$t}` AUTO_INCREMENT = " . ($maxId + 1));
        echo "Set AUTO_INCREMENT for $t to " . ($maxId+1) . "\n";
    }
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Restore finished. Please verify the application now.\n";
