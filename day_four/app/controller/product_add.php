    <!-- Đọc dữ liệu từ form  dùng php
    Xây form dùng html
    Ghi dữ liệu xuống database -->

<?php
require_once __DIR__."/../view/header.php";
require_once __DIR__."/../model/product.php";

$nameErr = $priceErr = $quantityErr =  "";
$name = $price = $quantity = "";


// bắt cảnh báo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // chỉ chưa kí tự và khoảng trắng
    // preg_match tìm kiếm mẫu trong một chuỗi ký tự, trả về true nếu mẫu tồn tại và false nếu ngược lại.
    // !preg_match("/^[a-zA-Z-' ']*$/"
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
    if (!filter_var($quantity, FILTER_VALIDATE_INT) && $quantity !== "0" && (int)$quantity < 0) {
      $quantityErr = "Invalid quantity";
    }
  }

  if ($nameErr === "" && $priceErr === "" && $quantityErr === ""){
    addProduct($name, $price, (int) $quantity);
    header("Location: /app/controller/product_list.php");
    exit;
  }

}

function test_input(string $data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  // Chức năng này htmlspecialchars()chuyển đổi các ký tự đặc biệt thành các thực thể HTML. 
  // Điều này có nghĩa là nó sẽ thay thế các ký tự HTML như 
  //  < và > bằng &lt: và  &gt;`.
  //  Điều này ngăn chặn kẻ tấn công khai thác mã bằng cách chèn mã HTML hoặc Javascript
  //   (tấn công Cross-site Scripting) vào các biểu mẫu.
  return $data;
}
?>

<h2> THÊM SẢN PHẨM, NHẬP DỮ LIỆU </h2>
<?php if ($nameErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($nameErr) ?></p>
<?php endif; ?>
<?php if ($priceErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($priceErr) ?></p>
<?php endif; ?>
<?php if ($quantityErr !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($quantityErr) ?></p>
<?php endif; ?>

<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  

  Name: <input type="text" name="name" value="<?php echo $name; ?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  Price: <input type="number" name="price" value="<?php echo $price; ?>">
  <span class="error">* <?php echo $priceErr;?></span>
  <br><br>
  Quantity: <input type="number" name="quantity" value="<?php echo $quantity;?>">
  <span class="error"><?php echo $quantityErr;?></span>
  <br><br>

  <button type="submit" name="submit" value="Submit">  Thêm </button>
  <!-- nếu hủy hiển thị danh sách cũ -->
  <a href="/app/controller/product_list.php">Hủy</a>  

</form>


<?php
require_once __DIR__."/../view/footer.php";
?>