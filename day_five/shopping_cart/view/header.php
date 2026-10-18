<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? "Quản lý sản phẩm";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <h1>Quản lý giỏ hàng</h1>
    <?php if (!empty($_SESSION['user_id'])): ?>
        <nav>
            Người dùng, <strong><?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
            | <a href="product_list.php">Danh sách sản phẩm</a>
            | <a href="logout.php">Đăng xuất</a>
        </nav>
    <?php endif; ?>
</header>
<main class="container">
