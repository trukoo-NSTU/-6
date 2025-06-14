<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
    redirect('index.php');
}

$product_id = $_POST['product_id'];
$rating = (int)$_POST['rating'];
$comment = trim($_POST['comment']);

if ($rating < 1 || $rating > 5) {
    $_SESSION['error_message'] = "Некорректная оценка";
    redirect('product.php?id=' . $product_id);
}

if (empty($comment)) {
    $_SESSION['error_message'] = "Комментарий не может быть пустым";
    redirect('product.php?id=' . $product_id);
}

if (addReview($product_id, $_SESSION['user_id'], $rating, $comment)) {
    $_SESSION['success_message'] = "Ваш отзыв добавлен";
} else {
    $_SESSION['error_message'] = "Не удалось добавить отзыв";
}

redirect('product.php?id=' . $product_id);
?>