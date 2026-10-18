<?php
require_once __DIR__ . '/common/auth.php';
requireLogin();

require_once __DIR__ . '/model/product.php';

$pageTitle = "Danh sách sản phẩm";
$products = getAllProducts();



require __DIR__ . '/view/header.php';
?>
<h2>Danh sách sản phẩm</h2>
<p><a class="button" href="product_add.php">+ Thêm sản phẩm</a></p>

<?php if (isset($_GET['message'])): ?>
    <p class="message"><?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>


<table>
    <thead>
        <tr><th>ID</th><th>Tên sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Chức năng</th></tr>
    </thead>

    <tbody>
    <?php if (!$products): ?>
        <tr><td colspan="5">Chưa có sản phẩm nào.</td></tr>
    <?php else: foreach ($products as $product): ?>
        <tr>
            <td><?= (int)$product['id'] ?></td>
            <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format((float)$product['price'], 2, ',', '.') ?></td>
            <td><?= (int)$product['quantity'] ?></td>
            <td class="actions">
                <a href="product_edit.php?id=<?= (int)$product['id'] ?>">Sửa</a>
                <a href="product_delete.php?id=<?= (int)$product['id'] ?>">Xóa</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>

</table>


<?php require __DIR__ . '/view/footer.php'; ?>