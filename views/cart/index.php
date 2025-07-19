<?php
session_start();
require_once __DIR__ . '/../../controllers/CartController.php';
$cartController = new CartController();
$subtotal = $cartController->getCartSubtotal();
$shipping = $cartController->calculateShipping($subtotal);
$address = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cep'])) {
    $address = $cartController->getAddressByCep($_POST['cep']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $couponResult = $cartController->applyCoupon($_POST['coupon_code']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize'])) {
    $finalizeResult = $cartController->finalizeOrder($_POST['cep'], $address ?? []);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/scripts.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Carrinho de Compras</h1>
        
        <!-- Lista de itens no carrinho -->
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($item['variation']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">Carrinho vazio</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Resumo do carrinho -->
        <div class="card mb-4">
            <div class="card-body">
                <p>Subtotal: R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                <p>Frete: R$ <?php echo number_format($shipping, 2, ',', '.'); ?></p>
                <?php if (isset($_SESSION['coupon'])): ?>
                    <p>Desconto (<?php echo htmlspecialchars($_SESSION['coupon']['code']); ?>): R$ <?php echo number_format($_SESSION['coupon']['discount'], 2, ',', '.'); ?></p>
                <?php endif; ?>
                <p>Total: R$ <?php echo number_format($subtotal + $shipping - ($_SESSION['coupon']['discount'] ?? 0), 2, ',', '.'); ?></p>
            </div>
        </div>

        <!-- Formulário de CEP -->
        <form id="cep-form" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" placeholder="Ex: 12345-678" maxlength="9">
                <div id="cep-error" class="text-danger"></div>
            </div>
            <div class="mb-3">
                <label for="street" class="form-label">Rua</label>
                <input type="text" class="form-control" id="street" name="street" readonly value="<?php echo htmlspecialchars($address['street'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="neighborhood" class="form-label">Bairro</label>
                <input type="text" class="form-control" id="neighborhood" name="neighborhood" readonly value="<?php echo htmlspecialchars($address['neighborhood'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="city" name="city" readonly value="<?php echo htmlspecialchars($address['city'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">Estado</label>
                <input type="text" class="form-control" id="state" name="state" readonly value="<?php echo htmlspecialchars($address['state'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Confirmar Endereço</button>
        </form>

        <!-- Formulário de Cupom -->
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="coupon_code" class="form-label">Código do Cupom</label>
                <input type="text" class="form-control" id="coupon_code" name="coupon_code">
            </div>
            <button type="submit" name="apply_coupon" class="btn btn-secondary">Aplicar Cupom</button>
        </form>

        <!-- Botão de Finalizar Pedido -->
        <form method="POST">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars(json_encode($address ?? [])); ?>">
            <button type="submit" name="finalize" class="btn btn-success">Finalizar Pedido</button>
        </form>

        <?php if (isset($couponResult)): ?>
            <div class="alert <?php echo isset($couponResult['error']) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($couponResult['error'] ?? $couponResult['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($finalizeResult)): ?>
            <div class="alert <?php echo isset($finalizeResult['error']) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($finalizeResult['error'] ?? $finalizeResult['success']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>