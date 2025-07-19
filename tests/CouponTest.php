<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controllers/CouponController.php';
require_once __DIR__ . '/../controllers/CartController.php';

class CouponTest extends TestCase {
    private $coupon;
    private $cart;

    protected function setUp(): void {
        $this->coupon = new CouponController();
        $this->cart = new CartController();
        $_SESSION['cart'] = [];
    }

    public function testCreateCoupon() {
        $result = $this->coupon->create('TESTE20', 20.00, 100.00, '2025-12-31');
        $this->assertEquals('Cupom cadastrado', $result['success']);
    }

    public function testApplyCoupon() {
        $this->coupon->create('TESTE20', 20.00, 100.00, '2025-12-31');
        
        // Simular carrinho com subtotal suficiente
        $_SESSION['cart'] = [['product_id' => 1, 'variation_id' => 1, 'quantity' => 2, 'price' => 60.00, 'variation' => 'Tamanho P']];
        $result = $this->cart->applyCoupon('TESTE20');
        $this->assertEquals('Cupom aplicado', $result['success']);

        // Testar subtotal insuficiente
        $_SESSION['cart'] = [['product_id' => 1, 'variation_id' => 1, 'quantity' => 1, 'price' => 30.00, 'variation' => 'Tamanho P']];
        $result = $this->cart->applyCoupon('TESTE20');
        $this->assertEquals('Subtotal insuficiente para o cupom', $result['error']);
    }
}
?>