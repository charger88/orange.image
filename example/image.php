<?php

require_once __DIR__.'/autoload.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$image = new Orange\Image\Image(__DIR__.'/image.jpg');

if ($action == 'enlarge'){
    $image->resize(null,360);
    $image->echoImage();
} else if ($action == 'reduce'){
    $image->resize(320);
    $image->echoImage();
} else if ($action == 'square'){
    $image->square(360);
    $image->echoImage();
} else if ($action == 'rectangle-unproportional'){
    $image->resize(480,240);
    $image->echoImage();
} else if ($action == 'rectangle-contain'){
    $image->rectangle(480,240,true);
    $image->echoImage();
} else if ($action == 'rectangle-transparent'){
    $image->setType('image/png');
    $image->setDefaultBackgroundColor(new \Orange\Image\Color(255,255,255,63));
    $image->rectangle(480,240,true);
    $image->echoImage();
} else if ($action == 'rectangle-contain-background'){
    $image->setDefaultBackgroundColor(new \Orange\Image\Color(255,255,255));
    $image->rectangle(480,240,true);
    $image->echoImage();
} else if ($action == 'rectangle-cover'){
    $image->rectangle(480,240,false);
    $image->echoImage();
} else if ($action == 'greyscale'){
    $image->greyscale();
    $image->echoImage();
} else if ($action == 'filter-greyscale'){
    $image->filter(IMG_FILTER_GRAYSCALE);
    $image->echoImage();
} else if ($action == 'compress'){
    $image->echoImage(51);
}  else if ($action == 'extreme-compress'){
    $image->echoImage(1);
} else if ($action == 'webp'){
    $image->setType('image/webp');
    $image->echoImage();
} else if ($action == 'webp-compressed'){
    $image->setType('image/webp');
    $image->echoImage(1);
} else {
    $image->echoImage();
}