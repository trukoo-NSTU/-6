<?php
require_once 'config.php';
require_once 'functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (empty($username)) {
        $errors[] = "Имя пользователя обязательно";
    }
    
    if (empty($password)) {
        $errors[] = "Пароль обязателен";
    }
    
    if (empty($errors)) {
        if (loginUser($username, $password)) {
            $_SESSION['success_message'] = "Вы успешно вошли в систему!";
            redirect('account.php');
        } else {
            $errors[] = "Неверное имя пользователя или пароль";
        }
    }
}

$page_title = "Вход в систему";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Вход в систему</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="post" class="form-container">
                <div class="form-group">
                    <label for="username">Имя пользователя</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Войти</button>
                <p class="mt-3 text-center">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>