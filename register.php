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
    $email = trim($_POST['email']);
    
    if (empty($username)) {
        $errors[] = "Имя пользователя обязательно";
    } elseif (strlen($username) < 4) {
        $errors[] = "Имя пользователя должно содержать минимум 4 символа";
    }
    
    if (empty($password)) {
        $errors[] = "Пароль обязателен";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать минимум 6 символов";
    }
    
    if (empty($email)) {
        $errors[] = "Email обязателен";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email";
    }
    
    if (empty($errors)) {
        try {
            if (registerUser($username, $password, $email)) {
                $_SESSION['success_message'] = "Регистрация прошла успешно! Теперь вы можете войти.";
                redirect('login.php');
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                $errors[] = "Это имя пользователя или email уже заняты";
            } else {
                $errors[] = "Произошла ошибка при регистрации";
            }
        }
    }
}

$page_title = "Регистрация";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="mb-4 text-center">Регистрация</h1>
            
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
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                <p class="mt-3 text-center">Уже есть аккаунт? <a href="login.php">Войдите</a></p>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>