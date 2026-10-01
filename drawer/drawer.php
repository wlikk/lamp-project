<?php
require_once __DIR__ . '/shapes.php';

$num    = (int)($_GET['num'] ?? 0);
$shape  = getShape($num);
$color  = getColor($num);
$width  = getWidth($num);
$height = getHeight($num);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Drawer</title>
</head>
<body>
    <h1>Drawer</h1>
    <p>num = <?= $num ?></p>

    <svg width="<?= $width ?>" height="<?= $height ?>">
    <?php if ($shape === 0): ?>
        <rect width="<?= $width ?>" height="<?= $height ?>" fill="<?= $color ?>"/>
    <?php elseif ($shape === 1): ?>
        <circle cx="<?= $width/2 ?>" cy="<?= $height/2 ?>" r="<?= min($width,$height)/2 ?>" fill="<?= $color ?>"/>
    <?php elseif ($shape === 2): ?>
        <ellipse cx="<?= $width/2 ?>" cy="<?= $height/2 ?>" rx="<?= $width/2 ?>" ry="<?= $height/2 ?>" fill="<?= $color ?>"/>
    <?php else: ?>
        <polygon points="<?= $width/2 ?>,0 <?= $width ?>,<?= $height ?> 0,<?= $height ?>" fill="<?= $color ?>"/>
    <?php endif; ?>
    </svg>

    <p>
        <a href="?num=0">0</a> |
        <a href="?num=1">1</a> |
        <a href="?num=2">2</a> |
        <a href="?num=3">3</a>
    </p>
</body>
</html>