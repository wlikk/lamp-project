<?php
require_once __DIR__ . '/Category.php';


$db = getDb();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

    case 'GET':
        if ($id !== null) {
            $row = Category::getById($db, $id);
            if (!$row) jsonResponse(['error' => 'Категория не найдена'], 404);
            jsonResponse($row);
        }
        jsonResponse(Category::getAll($db));
        break;

    case 'POST':
        $data = getInput();
        if (empty($data['name'])) {
            jsonResponse(['error' => 'Поле name обязательно'], 400);
        }
        $cat = new Category($data['name']);
        $newId = $cat->save($db);
        jsonResponse(['id' => $newId, 'name' => $cat->getName()], 201);
        break;

    case 'PUT':
        if ($id === null) jsonResponse(['error' => 'Не указан id'], 400);
        $data = getInput();
        if (empty($data['name'])) {
            jsonResponse(['error' => 'Поле name обязательно'], 400);
        }
        $cat = new Category($data['name'], $id);
        $cat->update($db);
        jsonResponse($cat->toArray());
        break;

    case 'DELETE':
        if ($id === null) jsonResponse(['error' => 'Не указан id'], 400);
        if (!Category::delete($db, $id)) {
            jsonResponse(['error' => 'Категория не найдена'], 404);
        }
        jsonResponse(['message' => 'Удалено']);
        break;

    default:
        jsonResponse(['error' => 'Метод не поддерживается'], 405);
}