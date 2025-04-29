<?php
session_start();
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../index.php");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>❌ كلمة المرور غير صحيحة.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>❌ البريد الإلكتروني غير مسجل.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">تسجيل الدخول</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-success w-100">دخول</button>
    </form>

    <p class="mt-3 text-center">
        لا تملك حسابًا؟ <a href="register.php">سجّل الآن</a>
    </p>
</div>

</body>
</html>