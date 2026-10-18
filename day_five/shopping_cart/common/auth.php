<!-- kiểm tra trạng thái đăng nhập -->
<!-- khởi động session, kiểm soát truy cập-->
<!-- VAI TRÒ CỦA SESSION
    Trong PHP, session dùng để lưu thông tin của người dùng giữa nhiều trang web,
    kể cả khi người dùng chuyển từ trang này sang trang khác.

    Bước 1: Đăng nhập tài khoản và mật khẩu ở login.php.
    Bước 2: Đăng nhập thành công, PHP lưu thông tin vào session.
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['username'] = $user['username'];    
    Bước 3: Khi chuyển sang product_list.php, PHP vẫn nhận biết đã đăng nhập.
    Bước 4: Khi đăng xuất, session bị xóa và phải đăng nhập lại.

    Nếu không dùng session, các trang PHP sẽ khó nhận biết người dùng đã đăng nhập ở trang trước hay chưa.

    Cách sử dụng session trong PHP:
    
    Bước 1: khởi tạo session
        session_start();
        Phải gọi hàm này trước khi sử dụng $_SESSION,
    Bước 2: Lưu dữ liệu vào session
        $_SESSION['user_id'] = n;
        $_SESSION['username'] = 'str';
    Bước 3: sử dụng session
        $_SESSION['user_id'];
        $_SESSION['username'];
    Bước 4: xóa session:
        session_destroy();
 -->
<?php

// kiểm tra trạng thái session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/// nếu session rỗng - đưa đén cửa sổ login
function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>