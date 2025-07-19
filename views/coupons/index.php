<?php
require_once __DIR__ . '/../../controllers/CouponController.php';
$controller = new CouponController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->create(
        $_POST['code'],
        floatval($_POST['discount']),
        floatval($_POST['min_value']),
        $_POST['valid_until']
    );
}
$coupons = $controller->index();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Cupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Gerenciar Cupons</h1>
        <?php if (isset($result)): ?>
            <div class="alert <?php echo isset($result['error']) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($result['error'] ?? $result['success']); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="code" class="form-label">Código do Cupom</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Desconto (R$)</label>
                <input type="number" step="0.01" class="form-control" id="discount" name="discount" required>
            </div>
            <div class="mb-3">
                <label for="min_value" class="form-label">Valor Mínimo (R$)</label>
                <input type="number" step="0.01" class="form-control" id="min_value" name="min_value" required>
            </div>
            <div class="mb-3">
                <label for="valid_until" class="form-label">Validade</label>
                <input type="date" class="form-control" id="valid_until" name="valid_until" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <h2>Lista de Cupons</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Desconto</th>
                    <th>Valor Mínimo</th>
                    <th>Validade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $coupon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                        <td>R$ <?php echo number_format($coupon['discount'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($coupon['min_value'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($coupon['valid_until'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../products/index.php" class="btn btn-secondary">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>