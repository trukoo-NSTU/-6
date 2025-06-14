<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_products = getUserProducts($user_id);

$page_title = "Личный кабинет";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Личный кабинет</h1>
            
            <div class="account-info mb-5">
                <h3>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
                <p>Здесь вы можете управлять своими товарами.</p>
                <a href="add-product.php" class="btn btn-primary">Добавить товар</a>
                <a href="logout.php" class="btn btn-danger">Выйти</a>
            </div>
            
            <h2 class="mb-4">Мои товары</h2>
            
            <?php if (empty($user_products)): ?>
                <div class="alert alert-info">У вас пока нет товаров. Добавьте свой первый товар!</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Название</th>
                                <th>Описание</th>
                                <th>Цена</th>
                                <th>Дата добавления</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_products as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</td>
                                    <td><?= number_format($product['price'], 2) ?> руб.</td>
                                    <td><?= date('d.m.Y H:i', strtotime($product['created_at'])) ?></td>
                                    <td>
                                        <a href="delete-product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">Удалить</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>