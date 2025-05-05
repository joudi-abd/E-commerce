<?php
require '../../includes/db.php'; // الاتصال بقاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, price, description) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $description]);

    header("Location: products.php");
    exit;
}
?>

<form method="post">
    <label>اسم المنتج:</label>
    <input type="text" name="name" required><br>

    <label>السعر:</label>
    <input type="number" name="price" required><br>

    <label>الوصف:</label>
    <textarea name="description"></textarea><br>

    <button type="submit">إضافة</button>
</form>