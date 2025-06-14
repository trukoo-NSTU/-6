<?php
require_once 'config.php';
require_once 'functions.php';

$categories = getAllCategories();
$products = getProducts();

$page_title = "Главная страница";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Категории</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="index.php">Все товары</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li class="list-group-item">
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
            <h1 class="mb-4">Последние товары</h1>
            
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card h-100">
                            <a href="product.php?id=<?= $product['id'] ?>">
                                <?php if ($product['image_path']): ?>
                                    <img src="<?= $product['image_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
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
                                <p class="category small text-muted">Категория: <?= $product['category_name'] ?? 'Без категории' ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>