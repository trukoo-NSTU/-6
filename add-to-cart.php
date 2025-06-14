<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    $_SESSION['error_message'] = "Для добавления в корзину необходимо войти в систему";
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
    redirect('index.php');
}

$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (addToCart($_SESSION['user_id'], $product_id, $quantity)) {
    $_SESSION['success_message'] = "Товар добавлен в корзину";
} else {
    $_SESSION['error_message'] = "Не удалось добавить товар в корзину";
}

redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php');
?>