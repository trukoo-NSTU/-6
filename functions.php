<?php
require_once 'config.php';

function getAllCategories() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProducts($category_id = null, $search = null) {
    global $conn;
    
    $sql = "SELECT p.*, u.username, c.name as category_name 
            FROM products p 
            JOIN users u ON p.user_id = u.id 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    
    $params = [];
    
    if ($category_id) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($search) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, u.username, c.name as category_name 
                          FROM products p 
                          JOIN users u ON p.user_id = u.id 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserProducts($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          WHERE p.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addProduct($name, $description, $price, $category_id, $user_id, $image_path = null) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, user_id, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $description, $price, $category_id, $user_id, $image_path]);
}

function updateProduct($id, $name, $description, $price, $category_id, $image_path = null) {
    global $conn;
    if ($image_path) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image_path = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $category_id, $image_path, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $category_id, $id]);
    }
}

function deleteProduct($product_id, $user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
    return $stmt->execute([$product_id, $user_id]);
}

function registerUser($username, $password, $email) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $hashed_password, $email]);
}

function loginUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

function getProductReviews($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
    $stmt->execute([$product_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addReview($product_id, $user_id, $rating, $comment) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$product_id, $user_id, $rating, $comment]);
}

function addToCart($user_id, $product_id, $quantity = 1) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    
    if ($stmt->rowCount() > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

function getCartItems($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.image_path 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.id 
                          WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateCartItem($user_id, $product_id, $quantity) {
    global $conn;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$quantity, $user_id, $product_id]);
}

function removeFromCart($user_id, $product_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    return $stmt->execute([$user_id, $product_id]);
}

function clearCart($user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}
function getCartTotal($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT SUM(p.price * c.quantity) as total 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.id 
                          WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}
?>