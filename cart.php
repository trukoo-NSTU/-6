<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($user_id);
$total = getCartTotal($user_id);

$page_title = "Корзина";
include 'header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Корзина</h1>
    
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">
            Ваша корзина пуста. <a href="index.php" class="alert-link">Перейти к покупкам</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= $item['image_path'] ?: 'images/no-image.png' ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <a href="product.php?id=<?= $item['product_id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                </div>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> руб.</td>
                            <td>
                                <form action="update-cart.php" method="post" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary mt-1">Обновить</button>
                                </form>
                            </td>
                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?> руб.</td>
                            <td>
                                <a href="remove-from-cart.php?product_id=<?= $item['product_id'] ?>" class="btn btn-sm btn-danger">Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                        <td><strong><?= number_format($total, 2) ?> руб.</strong></td>
                        <td>
                            <a href="checkout.php" class="btn btn-success">Оформить заказ</a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-outline-secondary">Продолжить покупки</a>
            <a href="checkout.php" class="btn btn-primary">Оформить заказ</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>