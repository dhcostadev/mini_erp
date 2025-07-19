<?php
session_start();
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/../scripts/send_email.php';

class CartController {
    private $productModel;
    private $orderModel;
    private $couponModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->couponModel = new Coupon();
    }

    public function addToCart($product_id, $variation_id, $quantity) {
        $product = $this->productModel->getById($product_id);
        $stock = null;
        foreach ($product as $p) {
            if ($p['stock_id'] == $variation_id) {
                $stock = $p;
                break;
            }
        }

        if (!$stock || $stock['quantity'] < $quantity) {
            return ['error' => 'Estoque insuficiente'];
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $quantity,
            'price' => $stock['price'],
            'variation' => $stock['variation']
        ];
        return ['success' => 'Produto adicionado ao carrinho'];
    }

    public function calculateShipping($subtotal) {
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15.00;
        } elseif ($subtotal > 200) {
            return 0.00;
        }
        return 20.00;
    }

    public function applyCoupon($code) {
        $coupon = $this->couponModel->getByCode($code);
        if (!$coupon) {
            return ['error' => 'Cupom inválido ou expirado'];
        }

        $subtotal = $this->getCartSubtotal();
        if ($subtotal < $coupon['min_value']) {
            return ['error' => 'Subtotal insuficiente para o cupom'];
        }

        $_SESSION['coupon'] = $coupon;
        return ['success' => 'Cupom aplicado'];
    }

    public function getCartSubtotal() {
        $subtotal = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }
        return $subtotal;
    }

    public function getAddressByCep($cep) {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cep) !== 8) {
            return ['error' => 'CEP inválido'];
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (isset($data['erro'])) {
            return ['error' => 'CEP não encontrado'];
        }

        return [
            'success' => true,
            'street' => $data['logradouro'] ?? '',
            'neighborhood' => $data['bairro'] ?? '',
            'city' => $data['localidade'] ?? '',
            'state' => $data['uf'] ?? ''
        ];
    }

    public function finalizeOrder($cep, $address) {
        $subtotal = $this->getCartSubtotal();
        $shipping = $this->calculateShipping($subtotal);
        $coupon_id = $_SESSION['coupon']['id'] ?? null;
        $address_json = json_encode($address);

        $order_id = $this->orderModel->create($subtotal, $shipping, $coupon_id, $cep, $address_json);

        // Enviar e-mail
        $email_result = sendOrderEmail($order_id, $address_json);

        // Limpar carrinho
        $_SESSION['cart'] = [];
        unset($_SESSION['coupon']);

        return ['success' => 'Pedido finalizado', 'order_id' => $order_id, 'email_result' => $email_result];
    }
}
?>