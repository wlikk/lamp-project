<?php
require_once __DIR__ . '/commands.php';

$cmd = $_GET['cmd'] ?? 'whoami';
$output = runCommand($cmd);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Инфо о сервере</title>
</head>
<body>
    <h1>Информация о сервере</h1>

    <p>
        <a href="?cmd=whoami">whoami</a> |
        <a href="?cmd=id">id</a> |
        <a href="?cmd=ls">ls</a> |
        <a href="?cmd=ps">ps</a> |
        <a href="?cmd=uname">uname</a> |
        <a href="?cmd=pwd">pwd</a> |
        <a href="?cmd=date">date</a> |
        <a href="?cmd=df">df</a>
    </p>

    <h2>Команда: <?= htmlspecialchars($cmd) ?></h2>
    <pre><?= htmlspecialchars($output) ?></pre>
</body>
</html>