<?php
session_start();
require_once __DIR__ . '/common/dbConnect.php';

$error = "";
$success = "";
$username = "";

// xử lý đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } 
    if (mb_strlen($username) > 50) {
        $error = "Tên đăng nhập không được quá 50 ký tự.";
    } else {
        // kiểm tra người dùng (cần cập nhật)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Tên đăng nhập đã tồn tại.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}

$pageTitle = "Đăng ký";
require __DIR__ . '/view/header.php';
?>


<h2>Đăng ký tài khoản</h2>

<!-- báo lỗi ngay trên form -->
<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post">
    <label>Tên đăng nhập
        <input type="text" name="username" maxlength="50" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" required>
    </label>
    <label>Mật khẩu
        <input type="password" name="password" required>
    </label>
    <button type="submit">Đăng ký</button>
    <a class="button" href="login.php">Đến đăng nhập</a>
</form>


<?php require __DIR__ . '/view/footer.php'; ?>