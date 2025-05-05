<?php
session_start();

// إذا لم يكن المستخدم مسجلاً الدخول، نحوله إلى login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!-- محتوى الصفحة الرئيسية -->
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الرئيسية</title>
</head>
<body>
    <h2>مرحبًا، <?php echo $_SESSION['username']; ?>!</h2>
    <a href="auth/logout.php">تسجيل الخروج</a>
</body>
</html>