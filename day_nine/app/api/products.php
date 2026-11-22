<?php
// api/products.php – REST API sản phẩm
declare(strict_types=1);




header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Đáp lại CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../common/dbconnect.php';

// Hàm phản hồi JSON

function sendResponse(bool $success, $data, string $message, int $status, ?array $pagination = null): void
{
    http_response_code($status);
    $payload = ['success' => $success];
    if ($data !== null) {
        $payload['data'] = $data;
    }
    if ($pagination !== null) {
        $payload['pagination'] = $pagination;
    }
    if ($message !== '') {
        $payload['message'] = $message;
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function ok($data, int $status = 200): void   { sendResponse(true,  $data, '',      $status); }
function okMsg(string $msg, int $s = 200): void { sendResponse(true,  null,  $msg,  $s); }
function fail(string $msg, int $s = 400): void  { sendResponse(false, null,  $msg,  $s); }
function okPage(array $data, array $pagination): void
{
    sendResponse(true, $data, '', 200, $pagination);
}

// kiểm tra dữ liệu đầu vào

function validateProduct(array $body): ?string
{
    // name
    if (!array_key_exists('name', $body)) {
        return 'Thiếu trường name.';
    }
    $name = trim((string)$body['name']);
    $len  = mb_strlen($name, 'UTF-8');
    if ($len < 1 || $len > 100) {
        return 'name phải có từ 1 đến 100 ký tự (sau khi bỏ khoảng trắng hai đầu).';
    }

    // price
    if (!array_key_exists('price', $body)) {
        return 'Thiếu trường price.';
    }
    if (!is_int($body['price']) || $body['price'] <= 0) {
        return 'price phải là số nguyên lớn hơn 0.';
    }

    // stock
    if (!array_key_exists('stock', $body)) {
        return 'Thiếu trường stock.';
    }
    if (!is_int($body['stock']) || $body['stock'] < 0) {
        return 'stock phải là số nguyên không âm.';
    }

    return null;
}

// Ép kiểu hàng MySQL → PHP
function castRow(array $row): array
{
    return [
        'id'    => (int)$row['id'],
        'name'  => (string)$row['name'],
        'price' => (int)$row['price'],
        'stock' => (int)$row['stock'],
        'image' => $row['image'] === null ? null : (string)$row['image'],
    ];
}

// Phân tích yêu cầu

$method = $_SERVER['REQUEST_METHOD'];
$body   = [];

if (in_array($method, ['POST', 'PUT'], true)) {
    $raw = (string)file_get_contents('php://input');
    if ($raw === '') {
        fail('Body JSON bị thiếu.', 400);
    }
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        fail('JSON không hợp lệ hoặc không ở dạng object.', 400);
    }
    $body = $decoded;
}

$idRaw = isset($_GET['id']) && is_string($_GET['id']) ? $_GET['id'] : null;

// Xử lý từng phương thức

try {
    $db = $conn;

    // ── GET: danh sách sản phẩm 
    if ($method === 'GET') {
        $pageValue = $_GET['page'] ?? '1';
        $limitValue = $_GET['limit'] ?? '5';
        if (!is_string($pageValue) || !is_string($limitValue)) {
            fail('Tham số page và limit phải là số nguyên.', 400);
        }
        $pageRaw = $pageValue;
        $limitRaw = $limitValue;
        if (!ctype_digit($pageRaw) || (int)$pageRaw < 1) {
            fail('Tham số page phải là số nguyên dương.', 400);
        }
        if (!ctype_digit($limitRaw) || (int)$limitRaw !== 5) {
            fail('Tham số limit phải bằng 5.', 400);
        }

        $requestedPage = (int)$pageRaw;
        $limit = 5;
        $count = $db->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $total = (int)$count;
        $totalPages = max(1, (int)ceil($total / $limit));
        $page = min($requestedPage, $totalPages);
        $offset = ($page - 1) * $limit;

        $stmt = $db->prepare(
            'SELECT id, name, price, stock, image
               FROM products
              ORDER BY id DESC
              LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        okPage(array_map('castRow', $rows), [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'total_pages' => $totalPages,
        ]);
    }

    // ── POST: thêm mới 
    if ($method === 'POST') {
        $err = validateProduct($body);
        if ($err !== null) {
            fail($err, 422);
        }

        $name  = trim((string)$body['name']);
        $price = (int)$body['price'];
        $stock = (int)$body['stock'];

        $stmt = $db->prepare(
            'INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)'
        );
        $stmt->execute([':name' => $name, ':price' => $price, ':stock' => $stock]);

        $newId = (int)$db->lastInsertId();

        $sel = $db->prepare(
            'SELECT id, name, price, stock, image FROM products WHERE id = :id'
        );
        $sel->execute([':id' => $newId]);

        ok(castRow($sel->fetch()), 201);
    }

    // ── PUT & DELETE: cần ?id= hợp lệ
    if ($method === 'PUT' || $method === 'DELETE') {

        if ($idRaw === null || !ctype_digit($idRaw) || (int)$idRaw <= 0) {
            fail('Tham số id trên URL bị thiếu hoặc không hợp lệ.', 400);
        }
        $id = (int)$idRaw;

        // Kiểm tra sản phẩm tồn tại
        $check = $db->prepare('SELECT id FROM products WHERE id = :id');
        $check->execute([':id' => $id]);
        if ($check->fetch() === false) {
            fail("Sản phẩm với id = {$id} không tồn tại.", 404);
        }

        // PUT: cập nhật 
        if ($method === 'PUT') {
            $err = validateProduct($body);
            if ($err !== null) {
                fail($err, 422);
            }

            $name  = trim((string)$body['name']);
            $price = (int)$body['price'];
            $stock = (int)$body['stock'];

            $stmt = $db->prepare(
                'UPDATE products
                    SET name = :name, price = :price, stock = :stock
                  WHERE id = :id'
            );
            $stmt->execute([
                ':name'  => $name,
                ':price' => $price,
                ':stock' => $stock,
                ':id'    => $id,
            ]);

            $sel = $db->prepare(
                'SELECT id, name, price, stock, image FROM products WHERE id = :id'
            );
            $sel->execute([':id' => $id]);
            ok(castRow($sel->fetch()));
        }

        // DELETE: xóa 
        $del = $db->prepare('DELETE FROM products WHERE id = :id');
        $del->execute([':id' => $id]);
        okMsg("Đã xóa sản phẩm id = {$id} thành công.");
    }

    // Phương thức không được hỗ trợ
    fail('Phương thức HTTP không được hỗ trợ.', 405);

} catch (PDOException $e) {
    fail('Lỗi cơ sở dữ liệu: ' . $e->getMessage(), 500);
} catch (Throwable $e) {
    fail('Lỗi máy chủ nội bộ.', 500);
}
