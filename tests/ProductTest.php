<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../models/Product.php';

class ProductTest extends TestCase {
    private $pdo;
    private $product;

    protected function setUp(): void {
        $this->pdo = new PDO('mysql:host=localhost;dbname=mini_erp', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->product = new Product();
    }

    public function testCreateProduct() {
        $variations = [
            ['variation' => 'Tamanho P', 'quantity' => 10],
            ['variation' => 'Tamanho M', 'quantity' => 20]
        ];
        $product_id = $this->product->create('Camiseta', 59.90, $variations);
        $this->assertIsInt($product_id);

        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Camiseta', $product['name']);
        $this->assertEquals(59.90, $product['price']);
    }

    public function testUpdateProduct() {
        $variations = [['variation' => 'Tamanho P', 'quantity' => 10]];
        $product_id = $this->product->create('Camiseta', 59.90, $variations);
        
        $new_variations = [['variation' => 'Tamanho G', 'quantity' => 15]];
        $this->product->update($product_id, 'Camiseta Atualizada', 69.90, $new_variations);
        
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('Camiseta Atualizada', $product['name']);
        $this->assertEquals(69.90, $product['price']);
    }
}
?>