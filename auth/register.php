<?php
// صفحة التسجيل
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<div class='alert alert-danger'>❌ البريد الإلكتروني مستخدم مسبقًا.</div>";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) 
                VALUES ('$username', '$email', '$password', '$role')";
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success'>✅ تم التسجيل بنجاح. يمكنك الآن تسجيل الدخول.</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ خطأ: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">إنشاء حساب جديد</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">اسم المستخدم</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">تسجيل</button>
    </form>

    <p class="mt-3 text-center">
        لديك حساب؟ <a href="login.php">سجّل الدخول</a>
    </p>
</div>

</body>
</html>