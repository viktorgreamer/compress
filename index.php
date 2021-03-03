<?php

use Imagine\Image\Box;

require 'vendor/autoload.php';
require 'functions.php';
$imagine = new Imagine\Gd\Imagine();
 $dir = "/home/viktor/Documents/learning/compress-medias";
// $dir = "/home/viktor/Documents/learning/compress-medias/Montenegro/Бали 2019";
$output_dir = "/home/viktor/Documents/learning/compressed-medias";
$tree = scandirrecurcive($dir);
$tree_existed = scandirrecurcive($output_dir);
$min_height = 720;
$min_width = 1280;

$count = 0;
foreach ($tree as $item) {
    $new_name = preg_replace("#$dir#", $output_dir, $item);
    if (in_array($new_name, $tree_existed)) {
        // echo "-";
        continue;
    }
    $count++;

    if (!file_exists(pathinfo($new_name, PATHINFO_DIRNAME))) mkdir(pathinfo($new_name, PATHINFO_DIRNAME), 0777, true);
    if ($count > 3000) break;
    if (strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'jpg') {
        $image = $imagine->open($item);
        $height = (int)$image->getSize()->getHeight();
        $width = (int)$image->getSize()->getWidth();

        if (($min_height < $height) && ($min_width < $width)) {

            $ratio = min($min_height / $height, $min_width / $width);
            //break;
            echo "+";
            $new_height = round($image->getSize()->getHeight() * $ratio);
            $new_width = round($image->getSize()->getWidth() * $ratio);
        } else {
            $new_height = round($image->getSize()->getHeight());
            $new_width = round($image->getSize()->getWidth());
        }

        $image->resize(new Box($new_width, $new_height))->save($new_name, array('jpeg_quality' => 50));

    } elseif (strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'mp4') {
        // shell_exec('ffmpeg -i ' . $item . ' -vf "scale=iw/1.5:ih/1.5" -b 2500k  -y ' . $new_name);
        $response = shell_exec("ffprobe -v quiet -print_format json -show_format -show_streams \"$item\"");
        $data = json_decode($response);
        $height = $data->streams[0]->height;
        $width = $data->streams[0]->width;
        if ($height > $width) $resolution = "720x1280"; else $resolution = "1280x720";
        echo "*";
        shell_exec("ffmpeg -i '$item' -s $resolution -b:v 5000k  -y '" . $new_name . "'");

        print_r($height);
        print_r($width);
        // sleep(4);
        echo round((filesize($new_name) / filesize($item)) * 100) . "%\n";
    } elseif (strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'mov') {
        // shell_exec('ffmpeg -i ' . $item . ' -vf "scale=iw/1.5:ih/1.5" -b 2500k  -y ' . $new_name);
        $response = shell_exec("ffprobe -v quiet -print_format json -show_format -show_streams \"$item\"");
        $data = json_decode($response);
        $height = $data->streams[0]->height;
        $width = $data->streams[0]->width;
        if ($height > $width) $resolution = "720x1280"; else $resolution = "1280x720";
        echo "*";
        shell_exec("ffmpeg -i '$item' -s $resolution -b:v 5000k  -vcodec h264 -acodec mp2 -y '" . $new_name . "'");

        print_r($height);
        print_r($width);
        // sleep(4);
        echo round((filesize($new_name) / filesize($item)) * 100) . "%\n";
    }
}
