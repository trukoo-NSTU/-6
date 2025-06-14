<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    redirect('cart.php');
}

$product_id = $_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($quantity < 1) {
    $_SESSION['error_message'] = "Количество должно быть не менее 1";
    redirect('cart.php');
}

if (updateCartItem($_SESSION['user_id'], $product_id, $quantity)) {
    $_SESSION['success_message'] = "Корзина обновлена";
} else {
    $_SESSION['error_message'] = "Не удалось обновить корзину";
}

redirect('cart.php');
?>