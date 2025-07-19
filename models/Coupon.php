<?php
require_once __DIR__ . '/../config/database.php';

class Coupon {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($code, $discount, $min_value, $valid_until) {
        $sql = "INSERT INTO coupons (code, discount, min_value, valid_until) 
                VALUES (:code, :discount, :min_value, :valid_until)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'code' => $code,
            'discount' => $discount,
            'min_value' => $min_value,
            'valid_until' => $valid_until
        ]);
        return $this->db->lastInsertId();
    }

    public function getByCode($code) {
        $sql = "SELECT * FROM coupons WHERE code = :code AND valid_until >= CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM coupons";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>