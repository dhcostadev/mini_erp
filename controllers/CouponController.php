<?php
require_once __DIR__ . '/../models/Coupon.php';

class CouponController {
    private $couponModel;

    public function __construct() {
        $this->couponModel = new Coupon();
    }

    public function index() {
        return $this->couponModel->getAll();
    }

    public function create($code, $discount, $min_value, $valid_until) {
        if (empty($code) || $discount <= 0 || $min_value < 0 || empty($valid_until)) {
            return ['error' => 'Dados inválidos'];
        }
        $this->couponModel->create($code, $discount, $min_value, $valid_until);
        return ['success' => 'Cupom cadastrado'];
    }
}
?>