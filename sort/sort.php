<?php
require_once __DIR__ . '/algorithms.php';

$input = $_GET['arr'] ?? '';
$arr = [];

if ($input !== '') {
    $arr = array_map('intval', explode(',', $input));
    $arr = selectionSort($arr);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сортировка</title>
</head>
<body>
    <h1>Сортировка выбором</h1>

    <form method="get">
        <input type="text" name="arr" value="<?= htmlspecialchars($input) ?>" size="50">
        <button type="submit">Сортировать</button>
    </form>

    <?php if ($input !== ''): ?>
        <p>Результат: <?= implode(', ', $arr) ?></p>
    <?php endif; ?>

    <p>Пример: <a href="?arr=5,3,8,1,9,2">?arr=5,3,8,1,9,2</a></p>
</body>
</html>