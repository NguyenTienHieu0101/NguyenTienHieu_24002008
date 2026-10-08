<?php
require_once __DIR__."/../model/product.php";
require_once __DIR__."/../view/header.php";

// lấy id từ đầu vào từ cả GET và POST
$id = isset($_GET["id"]) ? (int)$_GET["id"] : (int) ($_POST["id"] ?? 0);
$product = getProductById($id);

if (!$product) {
    require_once __DIR__."/../view/header.php";
    echo "<p>Sản phẩm không tồn tại.</p>";
    echo '<p><a href="/app/controller/product_list.php">Quay lại danh sách</a></p>';
    require_once __DIR__."/../view/footer.php";
    exit;
}

$nameErr = $priceErr = $quantityErr =  "";
$name = $product["name"];
$price = $product["price"];
$quantity = $product["quantity"];


// bắt cảnh báo và đầu vào
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // preg_match tìm kiếm mẫu trong một chuỗi ký tự, trả về true nếu mẫu tồn tại và false nếu ngược lại.
    if (!preg_match("/^[\p{L}\s]+$/u",$name)) {  
      $nameErr = "Tên không được rỗng.";
    }
  }

  if (empty($_POST["price"])) {
    $priceErr = "Price is required";
  } else {
    $price = test_input($_POST["price"]);
    // kiểm tra tính hợp lệ của price
    if (!is_numeric($price) || $price <= 0) {
      $priceErr = "Invalid price format";
    }
  }
  
  if (empty($_POST["quantity"])) {
    $quantityErr = "Quantity is required";
  } else {
    $quantity = test_input($_POST["quantity"]);
    if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
      $quantityErr = "Invalid quantity";
    }
  }

  if ($nameErr === "" && $priceErr === "" && $quantityErr === ""){
    updateProduct((int) $id, $name, $price, (int) $quantity);
    header("Location: /app/controller/product_list.php");  
    // gọi lại hiển thị
    exit;
  }

}

function test_input(string $data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2> CẬP NHẬT, NHẬP DỮ LIỆU </h2>

<?php if ($nameErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($nameErr) ?></p>
<?php endif; ?>
<?php if ($priceErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($priceErr) ?></p>
<?php endif; ?>
<?php if ($quantityErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($quantityErr) ?></p>
<?php endif; ?>

<!-- <p><span class="error">* required field</span></p> -->
 <!-- Đưa id vào form bằng hidden: -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

  Name: <input type="text" name="name" value="<?php echo $name; ?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  Price: <input type="number" name="price" value="<?php echo $price; ?>">
  <span class="error">* <?php echo $priceErr;?></span>
  <br><br>                                            <!--// >? = htmlspecialchars($quantity)  ?>  -->
  Quantity: <input type="number" name="quantity" value="<?php echo $quantity;?>">
  <span class="error"><?php echo $quantityErr;?></span>
  <br><br>

  <p style="color: red;"><span class="error">* required field</span></p>

  <button type="submit" name="submit" value="Submit">  Cập nhật </button>
  <!-- nếu hủy hiển thị danh sách cũ -->
  <a href="/app/controller/product_list.php">Hủy</a>  

</form>


<?php
require_once __DIR__."/../view/footer.php";
?>