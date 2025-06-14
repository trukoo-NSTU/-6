<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $quantity = (int)($_POST['quantity'] ?? 1);
    $address = trim($_POST['address'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    if (empty($address)) {
        $errors[] = "Адрес доставки обязателен";
    }

    if (empty($payment_method)) {
        $errors[] = "Выберите способ оплаты";
    }

    if (empty($errors)) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, address, payment_method, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$user_id, $product_id, $quantity, $address, $payment_method])) {
            clearCart($user_id);
            $_SESSION['success_message'] = "Заказ успешно оформлен!";
            redirect('account.php');
        } else {
            $errors[] = "Ошибка при оформлении заказа";
        }
    }
}

$page_title = "Оформление заказа";
include 'header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Оформление заказа</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php
            $cart_items = getCartItems($user_id);
            $total = getCartTotal($user_id);
            ?>

            <h3 class="mb-3">Ваш заказ</h3>
            <?php if (empty($cart_items)): ?>
                <div class="alert alert-info">Ваша корзина пуста. <a href="index.php" class="alert-link">Перейти к покупкам</a></div>
            <?php else: ?>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= number_format($item['price'], 2) ?> руб.</td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= number_format($item['price'] * $item['quantity'], 2) ?> руб.</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                <td><strong><?= number_format($total, 2) ?> руб.</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>

            <form action="checkout.php" method="post" class="form-container">
                <div class="form-group mb-3">
                    <label for="address">Адрес доставки</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Введите адрес доставки" required>
                </div>
                <div class="form-group mb-3">
                    <label for="payment_method">Способ оплаты</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="">Выберите способ оплаты</option>
                        <option value="card">Кредитная карта</option>
                        <option value="cash">Наличные при получении</option>
                        <option value="online">Онлайн-оплата</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Подтвердить заказ</button>
                <a href="cart.php" class="btn btn-secondary">Вернуться в корзину</a>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>