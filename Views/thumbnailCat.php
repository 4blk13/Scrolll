<?php

if (isset($_GET['url'])) {
    $img = file_get_contents($_GET['url']);
}
$im = imagecreatefromstring($img);
$width = imagesx($im);
$height = imagesy($im);
$newwidth = '200';
$newheight = '200';
$thumb = imagecreatetruecolor($newwidth, $newheight);
imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
header('Content-Type: image/jpeg');
imagejpeg($thumb, null, 100);

?>