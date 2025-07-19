<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';

class WebhookController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function handleWebhook() {
        $data = json_decode(file_get_contents('php://input'), true);
        $order_id = $data['order_id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$order_id || !$status) {
            http_response_code(400);
            return ['error' => 'Dados inválidos'];
        }

        if ($status === 'canceled') {
            $this->orderModel->delete($order_id);
            return ['success' => 'Pedido cancelado'];
        } else {
            $this->orderModel->updateStatus($order_id, $status);
            return ['success' => 'Status atualizado'];
        }
    }
}
?>