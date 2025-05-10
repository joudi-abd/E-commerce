<?php
require 'db.php';

// جدول المستخدمين
$usersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
)";

// جدول المنتجات
$productsTable = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255)
)";

// جدول السلة
$cartTable = "
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

// انشاء الجداول والتحقق منها
if (mysqli_query($conn, $usersTable)) {
    echo "Users table created successfully <br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $productsTable)) {
    echo "Products table created successfully <br>";
} else {
    echo "Error creating products table: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $cartTable)) {
    echo "Cart table created successfully <br>";
} else {
    echo "Error creating cart table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
