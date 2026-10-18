<?php
session_start();

// nếu chưa đăng nhập mở trang login
// nếu đã đăng nhập hiển thị danh sách sản phẩm

if (!empty($_SESSION['user_id'])) {
    header("Location: product_list.php");
} else {
    header("Location: login.php");
}
exit;
?>