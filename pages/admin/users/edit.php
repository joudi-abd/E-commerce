<?php
    session_start();
    require '../../../includes/db.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../../auth/login.php");
        exit;
    }

    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "المستخدم غير موجود";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email    = $_POST['email'];
        $role     = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }
?>
<form method="post">
    <label>الاسم:</label><br>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label>البريد:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label>الدور:</label><br>
    <select name="role" required>
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
    </select><br><br>

    <button type="submit">تحديث</button>
</form>