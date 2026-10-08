<?php
require_once __DIR__."/../model/product.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$product = getProductById($id);

if (!$product) {
    require_once "view/header.php";
    echo "<p>Sản phẩm không tồn tại.</p>";
    echo '<p><a href="/app/controller/product_list.php">Quay lại danh sách</a></p>';
    require_once "view/footer.php";
    exit;
}

deleteProduct($id);

header("Location: /app/controller/product_list.php");
exit;

?>