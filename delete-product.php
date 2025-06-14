<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isset($_GET['id'])) {
    redirect('index.php');
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if (deleteProduct($product_id, $user_id)) {
    $_SESSION['success_message'] = "Товар успешно удален!";
} else {
    $_SESSION['error_message'] = "Не удалось удалить товар или у вас нет прав на это действие";
}

redirect('account.php');
?>