<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Rose Store</title>
    <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>
    <h2>مرحبًا، <?php echo $_SESSION['username']; ?>!</h2>
    <a href="auth/logout.php">Logout </a>
</body>
</html>