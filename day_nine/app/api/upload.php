<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

function respond(bool $success, string $message, int $status, ?array $data = null): void
{
    http_response_code($status);
    $payload = ['success' => $success, 'message' => $message];
    if ($data !== null) {
        $payload['data'] = $data;
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Phương thức HTTP không được hỗ trợ.', 405);
}

$productIdRaw = $_POST['product_id'] ?? '';
if (!is_string($productIdRaw) || !ctype_digit($productIdRaw) || (int)$productIdRaw < 1) {
    respond(false, 'Mã sản phẩm bị thiếu hoặc không hợp lệ.', 400);
}
$productId = (int)$productIdRaw;

if (
    !isset($_FILES['image']) ||
    !is_array($_FILES['image']) ||
    !isset($_FILES['image']['error'], $_FILES['image']['tmp_name']) ||
    !is_int($_FILES['image']['error']) ||
    !is_string($_FILES['image']['tmp_name'])
) {
    respond(false, 'Vui lòng chọn ảnh cần tải lên.', 400);
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    $messages = [
        UPLOAD_ERR_INI_SIZE => 'Ảnh vượt quá dung lượng máy chủ cho phép.',
        UPLOAD_ERR_FORM_SIZE => 'Ảnh vượt quá dung lượng cho phép.',
        UPLOAD_ERR_PARTIAL => 'Ảnh chỉ được tải lên một phần. Vui lòng thử lại.',
        UPLOAD_ERR_NO_FILE => 'Vui lòng chọn ảnh cần tải lên.',
        UPLOAD_ERR_NO_TMP_DIR => 'Máy chủ không có thư mục tạm để nhận ảnh.',
        UPLOAD_ERR_CANT_WRITE => 'Máy chủ không thể ghi ảnh.',
        UPLOAD_ERR_EXTENSION => 'Máy chủ đã từ chối ảnh.',
    ];
    respond(false, $messages[$file['error']] ?? 'Không thể nhận ảnh tải lên.', 422);
}

$temporaryPath = (string)$file['tmp_name'];
if (!is_uploaded_file($temporaryPath)) {
    respond(false, 'File tải lên không hợp lệ.', 422);
}

$actualSize = filesize($temporaryPath);
if ($actualSize === false || $actualSize < 1 || $actualSize > 2 * 1024 * 1024) {
    respond(false, 'Ảnh phải có dung lượng lớn hơn 0 và không vượt quá 2 MB.', 422);
}

$imageInfo = getimagesize($temporaryPath);
$mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
$extensions = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
];
if ($imageInfo === false || !isset($extensions[$mimeType]) || $imageInfo['mime'] !== $mimeType) {
    respond(false, 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG hợp lệ.', 422);
}

require_once __DIR__ . '/../common/dbconnect.php';

$uploadDirectory = __DIR__ . '/../uploads';
if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
    respond(false, 'Không thể tạo thư mục lưu ảnh trên máy chủ.', 500);
}

try {
    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;
    $relativePath = 'uploads/' . $filename;

    $productQuery = $conn->prepare('SELECT image FROM products WHERE id = :id');
    $productQuery->execute([':id' => $productId]);
    $product = $productQuery->fetch();
    if ($product === false) {
        respond(false, "Sản phẩm với id = {$productId} không tồn tại.", 404);
    }

    if (!move_uploaded_file($temporaryPath, $destination)) {
        respond(false, 'Không thể lưu ảnh. Vui lòng thử lại.', 500);
    }

    try {
        $update = $conn->prepare('UPDATE products SET image = :image WHERE id = :id');
        $update->execute([':image' => $relativePath, ':id' => $productId]);
    } catch (Throwable $e) {
        if (is_file($destination)) {
            unlink($destination);
        }
        throw $e;
    }

    respond(true, 'Tải ảnh lên thành công.', 200, [
        'id' => $productId,
        'image' => $relativePath,
    ]);
} catch (PDOException $e) {
    respond(false, 'Lỗi cơ sở dữ liệu khi lưu ảnh.', 500);
} catch (Throwable $e) {
    respond(false, 'Lỗi máy chủ khi lưu ảnh.', 500);
}
