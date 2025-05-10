<?php
session_start();
require '../includes/db.php';

$email = "";
$password = "";
$errors = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $errors['email'] = "يرجى إدخال البريد الإلكتروني";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "البريد الإلكتروني غير صالح";
    }
    if (empty($password)) {
        $errors['password'] = "يرجى إدخال كلمة المرور";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: ../index.php");
                exit;
            } else {
                $errors['password'] = "كلمة المرور غير صحيحة";
            }
        } else {
            $errors['email'] = "البريد الإلكتروني غير مسجل";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../assets/css/login-style.css" rel="stylesheet">
</head>
<body dir="rtl">
    <div class="container">
        <div class="login">
            <h2>تسجيل دخول</h2>

            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="">
                <div>
                    <input type="email" class="input" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="البريد الإلكتروني">
                    <div>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="er"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="password" class="input" name="password" placeholder="كلمة المرور">
                    <div>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="er"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit">تسجيل الدخول</button>
            </form>
            <p>
                 ألا تملك حساباً؟ <a href="register.php">سجل الآن  </a>
            </p>
        </div>
        <div class="img">
            <div class="photo-frame">
                <img class="photo1" src="../assets/images/in-img/jewelry1.png" alt="jewelry1">
                <img class="photo2" src="../assets/images/in-img/jewelry2.png" alt="jewelry2">
            </div>
        </div>
    </div>

</body>
</html>