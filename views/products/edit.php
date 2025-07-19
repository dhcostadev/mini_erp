<?php
require_once __DIR__ . '/../../controllers/ProductController.php';
$controller = new ProductController();
$product_id = $_GET['id'] ?? 0;
$products = $controller->getById($product_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->update(
        $product_id,
        $_POST['name'],
        floatval($_POST['price']),
        $_POST['variations'] ?? []
    );
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Produto</h1>
        <?php if (isset($result)): ?>
            <div class="alert <?php echo isset($result['error']) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo htmlspecialchars($result['error'] ?? $result['success']); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($products[0]['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $products[0]['price']; ?>" required>
            </div>
            <div id="variations">
                <?php foreach ($products as $index => $product): ?>
                    <div class="mb-3">
                        <label for="variation_<?php echo $index; ?>" class="form-label">Variação</label>
                        <input type="text" class="form-control" name="variations[<?php echo $index; ?>][variation]" value="<?php echo htmlspecialchars($product['variation'] ?? ''); ?>" placeholder="Ex: Tamanho P">
                        <input type="number" class="form-control" name="variations[<?php echo $index; ?>][quantity]" value="<?php echo $product['quantity'] ?? 0; ?>" placeholder="Quantidade">
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addVariation()">Adicionar Variação</button>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </div>

    <script>
        let variationCount = <?php echo count($products); ?>;
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