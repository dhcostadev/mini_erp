<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        return $this->productModel->getAll();
    }

    public function create($name, $price, $variations) {
        if (empty($name) || $price <= 0 || empty($variations)) {
            return ['error' => 'Dados inválidos'];
        }
        $this->productModel->create($name, $price, $variations);
        return ['success' => 'Produto cadastrado'];
    }

    public function update($id, $name, $price, $variations) {
        if (empty($name) || $price <= 0 || empty($variations)) {
            return ['error' => 'Dados inválidos'];
        }
        $this->productModel->update($id, $name, $price, $variations);
        return ['success' => 'Produto atualizado'];
    }
}
?>