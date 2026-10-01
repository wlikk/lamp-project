<?php
require_once __DIR__ . '/db.php';

class Product {
    // Поля сущности (соответствуют полям таблицы products)
    private ?int $id;
    private string $name;
    private float $price;
    private int $categoryId;
    private ?string $categoryName = null;   // для JOIN-выборки

    public function __construct(
        string $name,
        float $price,
        int $categoryId,
        ?int $id = null
    ) {
        $this->name       = $name;
        $this->price      = $price;
        $this->categoryId = $categoryId;
        $this->id         = $id;
    }

    // Геттеры
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getCategoryId(): int { return $this->categoryId; }

    // Сеттеры
    public function setName(string $name): void { $this->name = $name; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setCategoryId(int $id): void { $this->categoryId = $id; }
    public function setCategoryName(string $name): void { $this->categoryName = $name; }

    // Представление в виде массива
    public function toArray(): array {
        $data = [
            'id'          => $this->id,
            'name'        => $this->name,
            'price'       => $this->price,
            'category_id' => $this->categoryId,
        ];
        if ($this->categoryName !== null) {
            $data['category_name'] = $this->categoryName;
        }
        return $data;
    }

    // ─── Методы работы с БД ────────────────────────────

    // Все товары (с названием категории)
    public static function getAll(mysqli $db): array {
        $sql = "SELECT p.id, p.name, p.price, p.category_id, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id";
        $result = $db->query($sql);
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $p = new Product($row['name'], (float)$row['price'], (int)$row['category_id'], (int)$row['id']);
            $p->setCategoryName($row['category_name']);
            $list[] = $p->toArray();
        }
        return $list;
    }

    // Один по id
    public static function getById(mysqli $db, int $id): ?array {
        $sql = "SELECT p.id, p.name, p.price, p.category_id, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) return null;
        $p = new Product($row['name'], (float)$row['price'], (int)$row['category_id'], (int)$row['id']);
        $p->setCategoryName($row['category_name']);
        return $p->toArray();
    }

    // Создать
    public function save(mysqli $db): int {
        $stmt = $db->prepare(
            "INSERT INTO products (name, price, category_id) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('sdi', $this->name, $this->price, $this->categoryId);
        $stmt->execute();
        $this->id = $db->insert_id;
        return $this->id;
    }

    // Обновить
    public function update(mysqli $db): bool {
        if ($this->id === null) return false;
        $stmt = $db->prepare(
            "UPDATE products SET name = ?, price = ?, category_id = ? WHERE id = ?"
        );
        $stmt->bind_param('sdii', $this->name, $this->price, $this->categoryId, $this->id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Удалить
    public static function delete(mysqli $db, int $id): bool {
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}