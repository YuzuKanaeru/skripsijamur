<?php
// Usage: php scripts/split_collage.php path/to/collage.jpg cols rows id1,id2,id3,...
// Example: php scripts/split_collage.php uploads/collage.jpg 4 2 3,4,5,6,7,8,9

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Penyakit;

if ($argc < 2) {
    echo "Usage: php scripts/split_collage.php path/to/collage.jpg [cols=4] [rows=2] [id_csv]\n";
    exit(1);
}

$collage = $argv[1];
$cols = isset($argv[2]) ? intval($argv[2]) : 4;
$rows = isset($argv[3]) ? intval($argv[3]) : 2;
$idCsv = isset($argv[4]) ? $argv[4] : null;

if (!file_exists($collage)) {
    echo "Collage file not found: $collage\n";
    exit(1);
}

$ids = [];
if ($idCsv) {
    $ids = array_filter(array_map('trim', explode(',', $idCsv)));
} else {
    // take first N penyakit ids from DB
    $needed = $cols * $rows;
    $ids = Penyakit::orderBy('id')->take(min($needed,7))->pluck('id')->toArray();
}

$count = count($ids);
if ($count == 0) {
    echo "No penyakit ids provided or found in DB.\n";
    exit(1);
}

$info = getimagesize($collage);
if ($info === false) { echo "Invalid image file.\n"; exit(1); }
$width = $info[0]; $height = $info[1];
$mime = $info['mime'];

switch ($mime) {
    case 'image/jpeg': $src = imagecreatefromjpeg($collage); break;
    case 'image/png': $src = imagecreatefrompng($collage); break;
    case 'image/gif': $src = imagecreatefromgif($collage); break;
    case 'image/webp': $src = function_exists('imagecreatefromwebp') ? imagecreatefromwebp($collage) : imagecreatefromjpeg($collage); break;
    default: echo "Unsupported collage image mime: $mime\n"; exit(1);
}

$tileW = intdiv($width, $cols);
$tileH = intdiv($height, $rows);

$dir = public_path('images/penyakit');
if (!file_exists($dir)) mkdir($dir,0755,true);

$index = 0;
for ($r = 0; $r < $rows; $r++) {
    for ($c = 0; $c < $cols; $c++) {
        if ($index >= $count) break 2;
        $id = $ids[$index];
        $x = $c * $tileW;
        $y = $r * $tileH;
        $min = min($tileW, $tileH);
        // create square crop from the tile: center crop
        $cropX = $x + max(0, intdiv($tileW - $min,2));
        $cropY = $y + max(0, intdiv($tileH - $min,2));

        $dst = imagecreatetruecolor(360,360);
        // preserve transparency when source PNG/GIF
        if (in_array($mime,['image/png','image/gif'])) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst,0,0,0,127));
            imagealphablending($dst,false);
            imagesavealpha($dst,true);
        }
        imagecopyresampled($dst, $src, 0,0, $cropX, $cropY, 360,360, $min, $min);

        $out = $dir.'/'.$id.'.jpg';
        // remove existing
        if (file_exists($out)) @unlink($out);
        imagejpeg($dst, $out, 85);
        imagedestroy($dst);
        echo "Wrote: $out\n";
        $index++;
    }
}

imagedestroy($src);

echo "Done. Created $index images for penyakit ids: ".implode(',',array_slice($ids,0,$index))."\n";
