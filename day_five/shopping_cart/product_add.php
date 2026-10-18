<?php
require_once __DIR__ . '/common/auth.php';
requireLogin();

require_once __DIR__ . '/model/product.php';

$pageTitle = "Thêm sản phẩm";

$error = "";
$name = "";
$price = "";
$quantity = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if ($name === '') {
        $error = "Tên sản phẩm không được để trống.";
    }
    if (!is_numeric($price) || (float)$price <= 0) {
        $error = "Giá sản phẩm phải lớn hơn 0.";
    } 
    if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
        $error = "Số lượng phải là số nguyên lớn hơn hoặc bằng 0.";
    } 

    if (empty($error)){
        if (addProduct($name, (float)$price, (int)$quantity)) {
            header('Location: product_list.php?msg=' . urlencode('Thêm sản phẩm thành công.'));
            exit;
        }
        $errors = 'Có lỗi xảy ra khi thêm sản phẩm.';
    }
}
require __DIR__ . '/view/header.php';
?>

<h2>Thêm sản phẩm</h2>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>


<form method="post" action="product_add.php" >
    <label>Tên sản phẩm
        <input type="text" name="name" maxlength="100" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required>
    </label>
    <label>Giá
        <input type="number" name="price" min="0.01" step="0.01" value="<?= htmlspecialchars((string)$price, ENT_QUOTES, 'UTF-8') ?>" required>
    </label>
    <label>Số lượng
        <input type="number" name="quantity" min="0" step="1" value="<?= htmlspecialchars((string)$quantity, ENT_QUOTES, 'UTF-8') ?>" required>
    </label>
    <button type="submit">Thêm</button>
    <a class="button" href="product_list.php">Quay lại</a>
</form>

<?php require __DIR__ . '/view/footer.php'; ?>