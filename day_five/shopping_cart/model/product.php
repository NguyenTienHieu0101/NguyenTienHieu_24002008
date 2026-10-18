<!-- cập nhật thêm kiểu giá trị trả về
     thu gọn câu lệnh tuy vấn
     kiểm tra XSS -->
<?php
require_once __DIR__ . '/../common/dbConnect.php';

function getAllProducts(): array {
    global $conn;
    $stmt = $conn->query("SELECT id, name, price, quantity FROM cart_items ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById(int $id): ?array {
    global $conn;
    $stmt = $conn->prepare("SELECT id, name, price, quantity FROM cart_items WHERE id = ?");
    $stmt->execute([(int)$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    return $product ?: null;
}

function addProduct(string $name,float $price, int $quantity): bool {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cart_items (name, price, quantity)
                            VALUES (?, ?, ?)");
    return $stmt->execute([trim($name), $price, $quantity]);
}

function updateProduct(int $id, string $name, float $price, int $quantity): bool {
    global $conn;
    $stmt = $conn->prepare("UPDATE cart_items SET name = ?, price = ?, quantity = ?
                            WHERE id = ?");
    $stmt->execute([trim($name), $price, $quantity, (int)$id]);
    return $stmt->rowCount() > 0;
}

function deleteProduct(int $id): bool {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
    $stmt->execute([(int)$id]);
    return $stmt->rowCount() > 0;
}
?>