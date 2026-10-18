<!-- Cửa sổ đăng nhập 
     rút gọn việc xử lý các biens báo lỗi
 -->

<?php
session_start();
require_once __DIR__ . '/common/dbConnect.php';

// kiểm tra session
if (!empty($_SESSION['user_id'])) {
    header("Location: product_list.php");
    exit;
}

$error = "";
$username = "";

// bắt phương thức POST sau khi nhấn đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Vui lòng nhập tên đăng nhập và mật khẩu.";
    } else {
        // truy cập database (cần cập nhật)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // cập nhật session
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: product_list.php");
            exit;
        }
        $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
    }
}
$pageTitle = "Đăng nhập";

require __DIR__ . '/view/header.php';
?>


<!-- form đăng nhập -->
<h2>Đăng nhập</h2>

<?php if (isset($_GET['registered'])): ?>
    <p class="message">Đăng ký thành công. Hãy đăng nhập.</p>
<?php endif; ?>

<!-- báo lỗi đăng nhập trên form -->
<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post">
    <label>Tên đăng nhập
        <input type="text" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" required>
    </label>
    <label>Mật khẩu
        <input type="password" name="password" required>
    </label>
    <button type="submit">Đăng nhập</button>
    <a class="button" href="register.php">Đăng ký</a>
</form>


<?php require __DIR__ . '/view/footer.php'; ?>