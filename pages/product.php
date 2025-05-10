<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productData = $result->fetch_assoc();

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $productData['id'],
                'name' => $productData['product_name'],
                'price' => $productData['price'],
                'image' => $productData['image'],
                'quantity' => 1
            ];
        }

        $_SESSION['success_message'] = "تمت إضافة المنتج إلى السلة بنجاح!";
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Product ID is missing.');
}

$product_id = intval($_GET['id']);

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
            max-height: 250px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            background-color: #fff;
            max-width: 700px;
            margin: auto;
        }
    </style>
</head>
<body dir="rtl">
    <div class="container py-5">
        <a href="../index.php" class="btn btn-secondary mb-4 d-block mx-auto" style="max-width: 200px;">العودة للصفحة الرئيسية</a>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-outline-success text-center"><?= $_SESSION['success_message'] ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="card p-4">
            <div class="row g-4 align-items-center">
                <div class="col-md-5 text-center">
                    <?php if (!empty($product['image'])) : ?>
                        <img src="../assets/images/pro-img/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-img">
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <h2 class="mb-3"><?= htmlspecialchars($product['product_name']) ?></h2>
                    <h4 class="text-success mb-3">سعر المنتج : <?= htmlspecialchars($product['price']) ?> $</h4>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <form action="" method="post" class="text-center">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-secondary mt-3">أضف إلى السلة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>