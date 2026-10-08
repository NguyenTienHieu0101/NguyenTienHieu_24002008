    <!-- Lấy dữ liệu lên từ Database thông qua model (quản lý sản phẩm)
    phương thức hiển thị dữ liệu lên bảng
    nhúng HTML -->

<?php
require_once __DIR__."/../view/header.php";
require_once __DIR__."/../model/product.php";

$products = getAllProducts();
?>

<h2>DANH SÁCH VẬT DÙNG </h2>
<div>
    <a href="/app/controller\product_add.php"> Mua thêm </a>
</div>
<div>
    <!-- cellpadding="8" cellspacing="0" -->
    <table border="2" >
        <tr><th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Chức năng</th></tr>
    <?php if (empty($products)): ?>
    <tr>
        <td colspan="5">Chưa có sản phẩm.</td>
    </tr>
    <?php else: ?>
        <?php foreach ($products as $pd): ?>
            <tr>
                <td><?=htmlspecialchars($pd["id"])?> </td>
                <td><?=htmlspecialchars($pd["name"])?> </td>
                <td><?=htmlspecialchars($pd["price"])?> </td>
                <td><?=htmlspecialchars($pd["quantity"])?> </td>
        <td>
            <!-- sửa và xóa cần đưa id vào hàm => ?id=  -->
             <!-- hiển thị cửa sổ xác nhận xóa  -->
            <a href="/app/controller/product_edit.php?id=<?=$pd["id"]?>"> Sửa </a> |
            <a href="/app/controller/product_delete.php?id=<?=$pd["id"]?>"
            onclick="return confirm('Có chắc muốn xóa sản phẩm này?');"
            >Xóa</a>
        </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </table>
</div>

<?php
require_once __DIR__."/../view/footer.php";
?>