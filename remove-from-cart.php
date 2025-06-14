<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['product_id'])) {
    redirect('cart.php');
}

$product_id = $_GET['product_id'];

if (removeFromCart($_SESSION['user_id'], $product_id)) {
    $_SESSION['success_message'] = "Товар удален из корзины";
} else {
    $_SESSION['error_message'] = "Не удалось удалить товар из корзины";
}

redirect('cart.php');
?>