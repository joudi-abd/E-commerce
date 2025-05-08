<?php
session_start();
require '../includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Product ID is missing.');
}

$product_id = intval($_GET['id']);

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Product not found!');
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['product_name']) ?> - تفاصيل المنتج</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Trebuchet, serif;
            background-color: rgb(250 215 232);
        }
        .product-img {
            max-width: 100%;
            border-radius: 10px;
        }
        img{
           
        }
    </style>
</head>
<body dir="rtl">
    <div class="container py-5">
        <a href="../index.php" class="btn btn-secondary mb-4">العودة للصفحة الرئيسية</a>
        <div class="row">
            <div class="col-md-4">
                <?php if (!empty($product['image'])) : ?>
                    <img src="../assets/images/pro-img/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-img">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h2><?= htmlspecialchars($product['product_name']) ?></h2>
                <h4 class="text-success mb-3">سعر المنتج : <?= htmlspecialchars($product['price']) ?> $</h4>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
        </div>
    </div>
</body>
</html>