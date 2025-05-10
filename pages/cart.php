<?php
session_start();
require '../includes/db.php';

// إذا كانت السلة غير موجودة في الجلسة، قم بإنشائها
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0; // إجمالي السلة

// عملية إضافة منتج للسلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // جلب تفاصيل المنتج من قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // إضافة المنتج أو تحديث الكمية
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['product_name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }

    // إعادة توجيه المستخدم إلى الصفحة الحالية بعد إضافة المنتج
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// عملية حذف منتج من السلة
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    // إعادة توجيه المستخدم إلى صفحة السلة بعد الحذف
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المشتريات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Trebuchet, serif;
            background-color: rgb(250 215 232);
        }
        .cart-img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            border-radius: 8px;
        }
        .card {
            border-radius: 10px;
            padding: 10px;
            background-color: #fff;
        }
        .card-body {
            text-align: center;
        }
        .card-title {
            font-size: 1.2em;
            color: #333;
        }
        .card-text {
            font-size: 1em;
        }
        .pro-img{
            text-align: center;
            margin:20px;
        }
        .card-img-top{
            max-width: 50%;
            border-radius: 8px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        } 
    </style>
</head>
<body dir="rtl">
    <div class="container py-5">
        <h2 class="mb-4 text-center">🛒 سلة المشتريات</h2>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>السلة فارغة حالياً.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($_SESSION['cart'] as $item):
                    $total += $item['price'] * $item['quantity'];
                ?>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="pro-img">
                                <img src="../assets/images/pro-img/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="Product Image">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                <p class="card-text">الكمية: <?= $item['quantity'] ?></p>
                                <p class="card-text">السعر: <?= $item['price'] * $item['quantity'] ?> $</p>
                                <a href="?id=<?= $item['id'] ?>" class="btn btn-outline-danger"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <h4>الإجمالي: <?= $total ?> $</h4>
                <a href="#" class="btn btn-outline-success mt-3">إتمام الشراء ✅</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
