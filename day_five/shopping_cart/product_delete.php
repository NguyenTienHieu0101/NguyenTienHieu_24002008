<?php
require_once __DIR__ . '/common/auth.php';
requireLogin();

require_once __DIR__ . '/model/product.php';

// lấy id 
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: product_list.php?message=" . urlencode("ID sản phẩm không hợp lệ."));
    exit;
}

$product = getProductById($id);
if (!$product) {
    header("Location: product_list.php?message=" . urlencode("Sản phẩm không tồn tại."));
    exit;
}

// xác nhận xóa (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirm'] ?? '') === 'yes') {
    if (deleteProduct($id)) {
        header("Location: product_list.php?message=" . urlencode("Đã xóa sản phẩm."));
    } else {
        header("Location: product_list.php?message=" . urlencode("Không thể xóa sản phẩm."));
    }
    exit;
}
$pageTitle = "Xóa sản phẩm";
require __DIR__ . '/view/header.php';
?>

<h2>Xác nhận xóa sản phẩm</h2>

<p>Bạn có chắc muốn xóa sản phẩm 
    <strong><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></strong>
    (ID: <?= (int)$id ?>) không?
</p>

<form method="post">
    <button class="danger" type="submit" name="confirm" value="yes">Đồng ý xóa</button>
    <a class="button" href="product_list.php">Hủy</a>
</form>

<?php require __DIR__ . '/view/footer.php'; ?>