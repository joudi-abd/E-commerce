<?php
require '../../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("معرّف المنتج غير صالح.");
}

// الاستعلام باستخدام MySQLi
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc(); // هنا نحصل على صف من قاعدة البيانات

if (!$product) {
    die("المنتج غير موجود.");
}

// التحديث عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $name, $price, $description, $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}
?>

<form method="post">
    <label>اسم المنتج:</label>
    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required><br>

    <label>السعر:</label>
    <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br>

    <label>الوصف:</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br>

    <button type="submit">تعديل</button>
</form>