<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../controllers/CartController.php';
$controller = new ProductController();
$cartController = new CartController();

$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $result = $controller->create(
        $_POST['name'],
        floatval($_POST['price']),
        $_POST['variations'] ?? []
    );
}
if ($action === 'add_to_cart') {
    $result = $cartController->addToCart(
        $_GET['id'],
        $_GET['variation_id'],
        1 // Quantidade fixa para simplificar
    );
}
$products = $controller->index();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Gerenciar Produtos</h1>
        <?php if (isset($result)): ?>
            <div class="alert <?php echo isset($result['error']) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($result['error'] ?? $result['success']); ?>
            </div>
        <?php endif; ?>
        <form action="?action=create" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div id="variations">
                <div class="mb-3">
                    <label for="variation_1" class="form-label">Variação</label>
                    <input type="text" class="form-control" name="variations[0][variation]" placeholder="Ex: Tamanho P">
                    <input type="number" class="form-control" name="variations[0][quantity]" placeholder="Quantidade">
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addVariation()">Adicionar Variação</button>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <h2>Lista de Produtos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Variações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($product['variation'] ?? '-'); ?> (<?php echo $product['quantity'] ?? 0; ?>)</td>
                        <td>
                            <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="?action=add_to_cart&id=<?php echo $product['id']; ?>&variation_id=<?php echo $product['stock_id']; ?>" class="btn btn-success">Comprar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        let variationCount = 1;
        function addVariation() {
            const div = document.createElement('div');
            div.className = 'mb-3';
            div.innerHTML = `
                <label class="form-label">Variação ${variationCount + 1}</label>
                <input type="text" class="form-control" name="variations[${variationCount}][variation]" placeholder="Ex: Tamanho M">
                <input type="number" class="form-control" name="variations[${variationCount}][quantity]" placeholder="Quantidade">
            `;
            document.getElementById('variations').appendChild(div);
            variationCount++;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>