<?php
require_once 'config.php';
require_once 'functions.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$products = [];

if (!empty($search_query)) {
    $products = getProducts(null, $search_query);
}

$page_title = "Результаты поиска: " . htmlspecialchars($search_query);
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Результаты поиска: "<?= htmlspecialchars($search_query) ?>"</h1>
            
            <?php if (empty($search_query)): ?>
                <div class="alert alert-warning">Пожалуйста, введите поисковый запрос</div>
            <?php elseif (empty($products)): ?>
                <div class="alert alert-info">По вашему запросу ничего не найдено</div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card product-card">
                                <?php if ($product['image_path']): ?>
                                    <img src="<?= $product['image_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                <?php else: ?>
                                    <div class="no-image">Нет изображения</div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                    <p class="price"><?= number_format($product['price'], 2) ?> руб.</p>
                                    <p class="category">Категория: <?= $product['category_name'] ?? 'Без категории' ?></p>
                                    <p class="seller">Продавец: <?= htmlspecialchars($product['username']) ?></p>
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