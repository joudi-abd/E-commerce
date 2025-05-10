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
        $errors['username'] = "Username is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 5) {
        $errors['password'] = "Password must be at least 5 characters long";
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
            $errors['email'] = "Email already in use.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $message = "<div class='alert success'>Registration successful, you can log in .</div>";
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
<body>
    <div class="container">
        <div class="img">
            <img src="../assets/images/reg-img/Logo.png" alt="Logo">
        </div>

        <div class="register">
            <h2>SIGN UP</h2>

            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="">
                <div>
                    <input type="text" class="input" id="username" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="USER NAME">
                    <div>
                        <?php if (isset($errors['username'])) : ?>
                            <p class="er"><?= $errors['username'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="email" class="input" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="E-MAIL" >
                    <div>
                        <?php if (isset($errors['email'])) : ?>
                            <p class="er"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="password" class="input" id="password" name="password" placeholder="PASSWORD">
                    <div>
                        <?php if (isset($errors['password'])) : ?>
                            <p class="er"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <input type="password" class="input" id="confirm_password" name="confirm_password" placeholder="CONFIRM PASSWORD">
                    <div>
                        <?php if (isset($errors['confirm_password'])) : ?>
                            <p class="er"><?= $errors['confirm_password'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit">SIGN UP</button>
            </form>

            <p>
                Already you have account?<a href="login.php">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
