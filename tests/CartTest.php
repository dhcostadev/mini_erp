<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../models/Product.php';

class CartTest extends TestCase {
    private $cart;
    private $product;

    protected function setUp(): void {
        $this->cart = new CartController();
        $this->product = new Product();
        $_SESSION['cart'] = [];
    }

    public function testAddToCart() {
        $variations = [['variation' => 'Tamanho P', 'quantity' => 10]];
        $product_id = $this->product->create('Camiseta', 59.90, $variations);
        $products = $this->product->getById($product_id);
        $variation_id = $products[0]['stock_id'];

        $result = $this->cart->addToCart($product_id, $variation_id, 1);
        $this->assertEquals('Produto adicionado ao carrinho', $result['success']);
        $this->assertCount(1, $_SESSION['cart']);
    }

    public function testCalculateShipping() {
        $this->assertEquals(20.00, $this->cart->calculateShipping(30));
        $this->assertEquals(15.00, $this->cart->calculateShipping(100));
        $this->assertEquals(0.00, $this->cart->calculateShipping(250));
    }

    public function testGetAddressByCep() {
        $result = $this->cart->getAddressByCep('01001000');
        $this->assertTrue($result['success']);
        $this->assertEquals('Praça da Sé', $result['street']);

        $result = $this->cart->getAddressByCep('00000000');
        $this->assertEquals('CEP não encontrado', $result['error']);
    }
}
?>