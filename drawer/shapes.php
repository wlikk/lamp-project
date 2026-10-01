<?php

function getShape(int $num): int {
    return $num & 3;
}

function getColor(int $num): string {
    $colors = ['red', 'green', 'blue', 'yellow'];
    return $colors[($num >> 2) & 3];
}

function getWidth(int $num): int {
    return (($num >> 4) & 255) + 50;
}

function getHeight(int $num): int {
    return (($num >> 12) & 255) + 50;
}