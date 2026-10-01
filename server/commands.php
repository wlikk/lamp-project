<?php

function runCommand(string $cmd): string {
    $allowed = ['whoami', 'id', 'ls', 'ps', 'uname', 'pwd', 'date', 'df'];
    if (!in_array($cmd, $allowed, true)) {
        return 'Команда не разрешена';
    }
    return shell_exec($cmd . ' 2>&1') ?? 'Нет вывода';
}