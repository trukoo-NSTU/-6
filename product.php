<?php
require_once 'config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$product_id = $_GET['id'];
$product = getProductById($product_id);

if (!$product) {
    $_SESSION['error_message'] = "Товар не найден";
    redirect('index.php');
}

$reviews = getProductReviews($product_id);

$page_title = htmlspecialchars($product['name']);
include 'header.php';
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <h1 class="mb-4"><?= htmlspecialchars($product['name']) ?></h1>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-img-top mb-3">
                        <?php if ($product['image_path']): ?>
                            <img src="<?= htmlspecialchars($product['image_path']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height: 300px; width: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="no-image text-center p-3" style="height: 300px; background-color: #f8f9fa;">Нет изображения</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <p class="mb-2"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="mb-2" style="font-weight: bold; color: #28a745;"><?= number_format($product['price'], 2) ?> руб.</p>
                        <p class="text-muted mb-2">Категория: <?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></p>
                        <p class="text-muted mb-3">Продавец: <?= htmlspecialchars($product['username']) ?></p>

                        <?php if (isLoggedIn()): ?>
                            <form action="add-to-cart.php" method="post" class="mb-3">
                                <div class="input-group" style="max-width: 250px;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control">
                                    <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <p class="text-muted mb-3">Войдите, чтобы добавить товар в корзину.</p>
                        <?php endif; ?>

                        <?php if (isLoggedIn()): ?>
                            <form action="checkout.php" method="post" class="mb-3">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Адрес доставки</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Введите адрес доставки" required>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Способ оплаты</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Выберите способ оплаты</option>
                                        <option value="card">Кредитная карта</option>
                                        <option value="cash">Наличные при получении</option>
                                        <option value="online">Онлайн-оплата</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Купить</button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted mb-3">Войдите, чтобы оформить покупку.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <h3 class="mb-3">Отзывы</h3>
            <?php if (empty($reviews)): ?>
                <div class="alert alert-info mb-4">Отзывов пока нет. Будьте первым!</div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($review['username']) ?> (Оценка: <?= $review['rating'] ?>/5)</h5>
                            <p class="card-text"><?= htmlspecialchars($review['comment']) ?></p>
                            <p class="text-muted small">Дата: <?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isLoggedIn()): ?>
                <h4 class="mb-3">Оставить отзыв</h4>
                <form action="add-review.php" method="post" class="mb-5">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Оценка</label>
                        <select class="form-control" id="rating" name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Комментарий</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                </form>
            <?php else: ?>
                <p class="text-muted mb-5">Войдите в систему, чтобы оставить отзыв.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>