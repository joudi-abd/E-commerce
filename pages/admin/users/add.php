<?php
session_start();
require '../../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<form method="post">
    <label>الاسم:</label><br>
    <input type="text" name="username" required><br>

    <label>البريد:</label><br>
    <input type="email" name="email" required><br>

    <label>كلمة المرور:</label><br>
    <input type="password" name="password" required><br>

    <label>الدور:</label><br>
    <select name="role" required>
        <option value="user">User</option>
    </select><br><br>

    <button type="submit">إضافة</button>
</form>