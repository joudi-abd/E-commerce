<?php
require 'db.php';

// جدول المستخدمين
$usersTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
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

// تنفيذ الاستعلامات
if (mysqli_query($conn, $usersTable)) {
    echo "تم إنشاء جدول users بنجاح<br>";
} else {
    echo "خطأ في إنشاء جدول users: " . mysqli_error($conn) . "<br>";
}

if (mysqli_query($conn, $productsTable)) {
    echo "تم إنشاء جدول products بنجاح<br>";
} else {
    echo "خطأ في إنشاء جدول products: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>