<?php
require 'ConnectDb.php';
$products = get_all_products();
$selectedCategory = $_GET['category'] ?? '';
$filteredProducts = get_products_by_category($selectedCategory);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products in <?= htmlspecialchars($selectedCategory); ?> Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Products in <?= htmlspecialchars($selectedCategory); ?> Category</h1>
    <div class="product-list">
        <?php if (count($filteredProducts) > 0): ?>
            <?php foreach ($filteredProducts as $product): ?>
                <div class='product'>
                    <h3><?= htmlspecialchars($product['title']); ?></h3>
                    <img src="<?= htmlspecialchars($product['cover']); ?>" alt="<?= htmlspecialchars($product['title']); ?> Cover" />
                    <p><strong>Description:</strong> <?= htmlspecialchars($product['description']); ?></p>
                    <p><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found in this category.</p>
        <?php endif; ?>
    </div>
</body>
</html>