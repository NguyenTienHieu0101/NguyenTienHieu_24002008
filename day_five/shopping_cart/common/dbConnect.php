<?php
$host = "127.0.0.1:3307";
$dbname = "shopping_cart";
$username = "root";
$password = ""; 

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Kết nối database thất bại." . $e->getMessage());
}
?>