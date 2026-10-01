<?php
require_once __DIR__ . '/db.php';

class Category {
    // Поля сущности (соответствуют полям таблицы categories)
    private ?int $id;
    private string $name;

    // Конструктор — инициализация полей
    public function __construct(string $name, ?int $id = null) {
        $this->name = $name;
        $this->id   = $id;
    }

    // Геттеры
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    // Сеттер
    public function setName(string $name): void {
        $this->name = $name;
    }

    // Представление в виде массива (для JSON-ответа)
    public function toArray(): array {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }

    // ─── Методы работы с БД ────────────────────────────

    // Получить все категории
    public static function getAll(mysqli $db): array {
        $result = $db->query("SELECT id, name FROM categories");
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = (new Category($row['name'], (int)$row['id']))->toArray();
        }
        return $list;
    }

    // Получить одну по id
    public static function getById(mysqli $db, int $id): ?array {
        $stmt = $db->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) return null;
        return (new Category($row['name'], (int)$row['id']))->toArray();
    }

    // Создать
    public function save(mysqli $db): int {
        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param('s', $this->name);
        $stmt->execute();
        $this->id = $db->insert_id;
        return $this->id;
    }

    // Обновить
    public function update(mysqli $db): bool {
        if ($this->id === null) return false;
        $stmt = $db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $this->name, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Удалить по id
    public static function delete(mysqli $db, int $id): bool {
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}