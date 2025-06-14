<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Online Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form action="search.php" method="get" class="d-flex mx-3">
                <input type="text" name="q" class="form-control me-2" placeholder="Поиск...">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Корзина</a>
                <?php if (isLoggedIn()): ?>
                    <a class="nav-link" href="account.php"><i class="bi bi-person"></i> Кабинет</a>
                    <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Выйти</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Вход</a>
                    <a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>