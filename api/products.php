<?php
require_once __DIR__ . '/Product.php';

$db = getDb();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

    case 'GET':
        if ($id !== null) {
            $row = Product::getById($db, $id);
            if (!$row) jsonResponse(['error' => 'Товар не найден'], 404);
            jsonResponse($row);
        }
        jsonResponse(Product::getAll($db));
        break;

    case 'POST':
        $data = getInput();
        if (empty($data['name']) || !isset($data['price']) || empty($data['category_id'])) {
            jsonResponse(['error' => 'Нужны поля name, price, category_id'], 400);
        }
        $p = new Product($data['name'], (float)$data['price'], (int)$data['category_id']);
        $newId = $p->save($db);
        jsonResponse(['id' => $newId] + $p->toArray(), 201);
        break;

    case 'PUT':
        if ($id === null) jsonResponse(['error' => 'Не указан id'], 400);
        $data = getInput();
        if (empty($data['name']) || !isset($data['price']) || empty($data['category_id'])) {
            jsonResponse(['error' => 'Нужны поля name, price, category_id'], 400);
        }
        $p = new Product($data['name'], (float)$data['price'], (int)$data['category_id'], $id);
        $p->update($db);
        jsonResponse($p->toArray());
        break;

    case 'DELETE':
        if ($id === null) jsonResponse(['error' => 'Не указан id'], 400);
        if (!Product::delete($db, $id)) {
            jsonResponse(['error' => 'Товар не найден'], 404);
        }
        jsonResponse(['message' => 'Удалено']);
        break;

    default:
        jsonResponse(['error' => 'Метод не поддерживается'], 405);
}