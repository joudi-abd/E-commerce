<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// جلب المنتجات من قاعدة البيانات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>المنتجات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            padding: 20px;
        }
        .product {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            max-width: 500px;
        }
        .product img {
            max-width: 150px;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>المنتجات المتاحة</h2>

    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <div class="product">
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>السعر:</strong> <?= htmlspecialchars($product['price']) ?>$</p>
                <?php if (!empty($product['image'])) : ?>
                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="صورة المنتج">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>لا توجد منتجات حاليًا.</p>
    <?php endif; ?>

</body>
</html>