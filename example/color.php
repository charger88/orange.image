<?php

require_once __DIR__.'/autoload.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$image = new Orange\Image\Image(__DIR__.'/image.jpg');

?><html>
<head></head>
<body style="margin: 0; ;padding: 0;">
<div style="text-align: center; padding: 50px 30px; height: 320px; background: <?php echo $image->getAverageColor(); ?>;"><img src="image.jpg" alt="" /></div>
<ul>
    <?php for ($i = 0;$i < 64;$i++){ ?>
    <li style="color: <?php echo $image->getPixelColor($i,$i); ?>;"><?php echo $i; ?>:<?php echo $i; ?> <?php echo $image->getPixelColor($i,$i); ?></li>
    <?php } ?>
</ul>
</body>
</html>
