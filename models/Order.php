<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function create($subtotal, $shipping_fee, $coupon_id, $cep, $address) {
        $sql = "INSERT INTO orders (subtotal, shipping_fee, coupon_id, cep, address, status) 
                VALUES (:subtotal, :shipping_fee, :coupon_id, :cep, :address, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'coupon_id' => $coupon_id,
            'cep' => $cep,
            'address' => $address
        ]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
?>