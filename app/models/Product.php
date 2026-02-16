<?php

class Product {

    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM products")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        if (empty($data['name']) || !is_string($data['name'])) {
            throw new InvalidArgumentException('Product name is required and must be string');
        }

        $name = trim($data['name']);
        if (strlen($name) === 0 || strlen($name) > 255) {
            throw new InvalidArgumentException('Product name must be between 1 and 255 characters');
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || (float)$data['price'] <= 0) {
            throw new InvalidArgumentException('Valid price is required and must be greater than 0');
        }

        $price = (float)$data['price'];
        if ($price > 999999.99) {
            throw new InvalidArgumentException('Price is too high');
        }

        $stock = isset($data['stock']) ? (int)$data['stock'] : 0;
        if ($stock < 0) {
            throw new InvalidArgumentException('Stock cannot be negative');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (name, price, stock)
             VALUES (:name, :price, :stock)"
        );

        $result = $stmt->execute([
            'name' => $name,
            'price' => $price,
            'stock' => $stock
        ]);

        if (!$result) {
            throw new Exception('Failed to create product');
        }

        return $this->db->lastInsertId();
    }
}
