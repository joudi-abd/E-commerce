<?php
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
        $errors['username'] = "يرجى إدخال اسم المستخدم";
    }

    if (empty($email)) {
        $errors['email'] = "يرجى إدخال البريد الإلكتروني";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "البريد الإلكتروني غير صالح";
    }

    if (empty($password)) {
        $errors['password'] = "يرجى إدخال كلمة المرور";
    } elseif (strlen($password) < 5) {
        $errors['password'] = "يجب ان تكون كلمة المرور 5 أحرف على الأقل";
    }

    if (empty($confirm_password)) {
        $errors['confirm_password'] = "يرجى تأكيد كلمة المرور";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "كلمات المرور غير متطابقة";
    }


    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['email'] = "البريد الإلكتروني مستخدم سابقا";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $message = "<div class='alert success'>تم التسجيل بنجاح , يمكنك تسجيل الدخول .</div>";
                $username = $email = $password = $confirm_password = "";
            } else {
                $message = "<div class='alert error'>Error: " . $stmt->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="../assets/css/register-style.css" rel="stylesheet">
</head>
<body dir="rtl">
    <div class="container">
        <div class="img">
            <img src="../assets/images/reg-img/Logo.png" alt="Logo">
        </div>

        <div class="register">
            <h2>تسجيل حساب </h2>

            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="">
                <div>
                    <input type="text" class="input" id="username" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="اسم المستخدم">
                    <div>
                        <?php if (isset($errors['username'])) : ?>
                            <p class="er"><?= $errors['username'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="email" class="input" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="البريد الإلكتروني" >
                    <div>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="er"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="password" class="input" id="password" name="password" placeholder="كلمة المرور">
                    <div>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="er"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="password" class="input" id="confirm_password" name="confirm_password" placeholder="تأكيد كلمة المرور">
                    <div>
                        <?php if (isset($errors['confirm_password'])) : ?>
                            <p class="er"><?= $errors['confirm_password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit">تسجيل</button>
            </form>

            <p>
                لديك حساب بالفعل؟ <a href="login.php">تسجيل دخول </a>
            </p>
        </div>
    </div>
</body>
</html>
