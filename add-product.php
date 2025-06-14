<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$categories = getAllCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    
    if (empty($name)) {
        $errors[] = "Название товара обязательно";
    }
    
    if (empty($price)) {
        $errors[] = "Цена товара обязательна";
    } elseif (!is_numeric($price)) {
        $errors[] = "Цена должна быть числом";
    }
    
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $errors[] = "Файл не является изображением";
        }
        
        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            $errors[] = "Разрешены только JPG, JPEG, PNG и GIF файлы";
        }
        
        if ($_FILES['image']['size'] > 2000000) {
            $errors[] = "Файл слишком большой. Максимальный размер 2MB";
        }
        
        if (empty($errors)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                $errors[] = "Произошла ошибка при загрузке файла";
            }
        }
    }
    
    if (empty($errors)) {
        if (addProduct($name, $description, $price, $category_id, $_SESSION['user_id'], $image_path)) {
            $_SESSION['success_message'] = "Товар успешно добавлен!";
            redirect('account.php');
        } else {
            $errors[] = "Произошла ошибка при добавлении товара";
        }
    }
}

$page_title = "Добавить товар";
include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="mb-4">Добавить новый товар</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data" class="form-container">
                <div class="form-group">
                    <label for="name">Название товара</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Цена (руб.)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Категория</label>
                    <select class="form-control" id="category_id" name="category_id">
                        <option value="">Без категории</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Изображение товара</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                
                <button type="submit" class="btn btn-primary">Добавить товар</button>
                <a href="account.php" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>