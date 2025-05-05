<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
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
    <title>إدارة المنتجات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            padding: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            max-width: 600px;
        }
        .actions a {
            margin-left: 10px;
            text-decoration: none;
            color: blue;
        }
        .add-product {
            margin-bottom: 20px;
        }
        img {
            max-width: 120px;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>لوحة التحكم - إدارة المنتجات</h2>

    <div class="add-product">
        <a href="add_product.php">➕ إضافة منتج جديد</a>
    </div>

    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $product) : ?>
            <div class="product">
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>السعر:</strong> <?= htmlspecialchars($product['price']) ?>$</p>
                <?php if (!empty($product['image'])) : ?>
                    <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="صورة المنتج">
                <?php endif; ?>
                <div class="actions">
                    <a href="edit_product.php?id=<?= $product['id'] ?>">✏ تعديل</a>
                    <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑 حذف</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>لا توجد منتجات حاليًا.</p>
    <?php endif; ?>

</body>
</html>