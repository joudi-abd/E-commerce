<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <a href="products.php">View Products</a>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="admin/products.php">Manage Products</a>
        <a href="admin/users/index.php">Manage Users</a>
    <?php endif; ?>
</body>
</html>