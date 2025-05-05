<?php
    session_start();
    require '../../../includes/db.php';

    // التحقق من أن المستخدم هو أدمن
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../../auth/login.php");
        exit;
    }

    // جلب جميع المستخدمين باستثناء الأدمن
    $stmt = $conn->prepare("SELECT * FROM users WHERE role != 'admin'");
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        a { text-decoration: none; margin: 0 5px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">قائمة المستخدمين (باستثناء الأدمن)</h2>

    <table>
        <thead>
            <tr>
                <th>المعرف</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id'] ?>">تعديل</a>
                        |
                        <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>