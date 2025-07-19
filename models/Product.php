<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($name, $price, $variations) {
        $sql = "INSERT INTO products (name, price) VALUES (:name, :price)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name, 'price' => $price]);
        $product_id = $this->db->lastInsertId();

        foreach ($variations as $variation) {
            $sql = "INSERT INTO stocks (product_id, variation, quantity) VALUES (:product_id, :variation, :quantity)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'product_id' => $product_id,
                'variation' => $variation['variation'],
                'quantity' => $variation['quantity']
            ]);
        }
        return $product_id;
    }

    public function update($id, $name, $price, $variations) {
        $sql = "UPDATE products SET name = :name, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price]);

        $sql = "DELETE FROM stocks WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['product_id' => $id]);

        foreach ($variations as $variation) {
            $sql = "INSERT INTO stocks (product_id, variation, quantity) VALUES (:product_id, :variation, :quantity)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'product_id' => $id,
                'variation' => $variation['variation'],
                'quantity' => $variation['quantity']
            ]);
        }
    }

    public function getAll() {
        $sql = "SELECT p.*, s.variation, s.quantity, s.id AS stock_id 
                FROM products p 
                LEFT JOIN stocks s ON p.id = s.product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT p.*, s.variation, s.quantity, s.id AS stock_id 
                FROM products p 
                LEFT JOIN stocks s ON p.id = s.product_id 
                WHERE p.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>