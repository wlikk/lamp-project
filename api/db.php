<?php

// Заголовки для JSON и CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Подключение к БД
function getDb(): mysqli {
    $conn = new mysqli('db', 'user', 'password', 'appDB');
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка подключения к БД']);
        exit;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Отдать JSON и выйти
function jsonResponse($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Прочитать тело запроса (для POST/PUT)
function getInput(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}