<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id       = $_POST['id'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body{
          background-color:#f0bdbd; 
          font-family: Trebuchet, serif ;
        }
        .container{
          background-color:#faebd7;
          border-radius: 10px;
        }
        table { 
          margin: 20px auto;
        }
        th, td {
          text-align: center;
        }
        a {
          text-decoration: none; 
          margin: 5px;
        }
        .dang:hover{
            background-color: red;
        }
    </style>
</head>
<body dir="rtl">
<div class="container">
    <h2 style="text-align:center; margin:20px 0 0 0">قائمة المستخدمين</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th style="background-color:#f0bdbd;">المعرف</th>
                    <th style="background-color:#f0bdbd;">الاسم</th>
                    <th style="background-color:#f0bdbd;">الإيميل</th>
                    <th style="background-color:#f0bdbd;">الدور</th>
                    <th style="background-color:#f0bdbd;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) : ?>
                    <tr>
                        <td style="background-color:#faebd7;"><?= $user['id'] ?></td>
                        <td style="background-color:#faebd7;"><?= htmlspecialchars($user['username']) ?></td>
                        <td style="background-color:#faebd7;"><?= htmlspecialchars($user['email']) ?></td>
                        <td style="background-color:#faebd7;"><?= $user['role'] ?></td>
                        <td style="background-color:#faebd7;">
                            <div class="btn-group" dir="ltr">
                                <button class="btn btn-outline-secondary deleteBtn"
                                        data-id="<?= $user['id'] ?>" 
                                        data-username="<?= htmlspecialchars($user['username']) ?>" 
                                        data-email="<?= htmlspecialchars($user['email']) ?>" 
                                        data-role="<?= $user['role'] ?>" 
                                        data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button class="btn btn-outline-secondary deleteBtn dang" 
                                    data-id="<?= $user['id'] ?>" 
                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>    
        </table>
    </div>
    <div style="text-align:center;">
         <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم جديد</a>
    </div>
</div>
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#f0bdbd; justify-content:space-between;">
        <h5 class="modal-title" id="editUserModalLabel">تعديل المستخدم</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin:0px"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="edit_user" value="1">
          <div class="mb-3">
            <label class="form-label">الأسم:</label>
            <input type="text" name="username" id="edit-username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">الإيميل:</label>
            <input type="email" name="email" id="edit-email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">الدور:</label>
            <select name="role" id="edit-role" class="form-select" required>
              <option value="user">مستخدم</option>
              <option value="admin">أدمن</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-outline-secondary" style="background-color:#f0bdbd;">تحديث</button>
        </div>

      </form>

    </div>
  </div>
</div>
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" style="background-color:#f0bdbd; justify-content:space-between; ">
        <h5 class="modal-title" id="deleteUserModalLabel">تأكيد الحذف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin:0px"></button>
      </div>

      <form method="post">
        <div class="modal-body">
          <p>هل انت متأكد من حذف هذا المستخدم؟</p>
          <input type="hidden" name="id" id="delete-id">
          <input type="hidden" name="delete_user" value="1">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-outline-danger">حذف</button>
        </div>
      </form>

    </div>
  </div>
</div>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header" style="background-color:#f0bdbd; justify-content:space-between;">
        <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin:0px"></button>
      </div>

      <form method="post">
        <div class="modal-body">

          <input type="hidden" name="add_user" value="1">

          <div class="mb-3">
            <label class="form-label">الاسم:</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">الإيميل:</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">كلمة المرور:</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">الدور:</label>
            <select name="role" class="form-select" required>
              <option value="user">مستخدم</option>
              <option value="admin">أدمن</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-outline-secondary" style="background-color:#f0bdbd;">إضافة</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-username').value = button.getAttribute('data-username');
        document.getElementById('edit-email').value = button.getAttribute('data-email');
        document.getElementById('edit-role').value = button.getAttribute('data-role');
    });
});
</script>
<script>
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('edit-id').value = button.getAttribute('data-id');
        document.getElementById('edit-username').value = button.getAttribute('data-username');
        document.getElementById('edit-email').value = button.getAttribute('data-email');
        document.getElementById('edit-role').value = button.getAttribute('data-role');
    });
});

document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('delete-id').value = button.getAttribute('data-id');
    });
});
</script>

</body>
</html>