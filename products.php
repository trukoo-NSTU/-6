<?php
require_once 'config.php';
require_once 'functions.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$categories = getAllCategories();
$products = getProducts($category_id);

$category_name = $category_id ? array_filter($categories, fn($c) => $c['id'] == $category_id)[array_key_first(array_filter($categories, fn($c) => $c['id'] == $category_id))]['name'] ?? 'Категория' : 'Все товары';
$page_title = "Товары: " . htmlspecialchars($category_name);
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4 categories-sidebar">
                <div class="card-body">
                    <h3 class="card-title">Категории</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="index.php">Все товары</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li class="list-group-item <?= $category['id'] == $category_id ? 'active' : '' ?>">
                                <a href="products.php?category_id=<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <h1 class="mb-4">Товары: <?= htmlspecialchars($category_name) ?></h1>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info">Товаров в этой категории пока нет.</div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100">
                                <a href="product.php?id=<?= $product['id'] ?>">
                                    <?php if ($product['image_path']): ?>
                                        <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php else: ?>
                                        <div class="no-image">Нет изображения</div>
                                    <?php endif; ?>
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="product.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                    <p class="price text-primary"><?= number_format($product['price'], 2) ?> руб.</p>
                                    <p class="category small text-muted">Категория: <?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>