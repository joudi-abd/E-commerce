<?php
// صفحة التسجيل
require '../includes/db.php';

$username = "";
$email = "";
$password = "";
$confirm_password = "";
$errors = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user';

    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    }

    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Confirm password is required";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }


    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['email'] = "<div class='alert alert-danger'> البريد الإلكتروني مستخدم مسبقًا.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>✅ تم التسجيل بنجاح. يمكنك الآن تسجيل الدخول.</div>";
                $username = $email = $password = $confirm_password = "";
            } else {
                $message = "<div class='alert alert-danger'>❌ خطأ: " . $stmt->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="../assests/css/register-style.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center mb-4">إنشاء حساب جديد</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                <div>
                    <?php if (isset($errors['username'])) : ?>
                        <p style="color: red;"><?= $errors['username'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                <div>
                    <?php if (isset($errors['email'])) : ?>
                        <p style="color: red;"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div>
                    <?php if (isset($errors['password'])) : ?>
                        <p style="color: red;"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <div>
                    <?php if (isset($errors['confirm_password'])) : ?>
                        <p style="color: red;"><?= $errors['confirm_password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">تسجيل</button>
        </form>

        <p class="mt-3 text-center">
            لديك حساب؟ <a href="login.php">سجّل الدخول</a>
        </p>
    </div>

</body>
</html>
