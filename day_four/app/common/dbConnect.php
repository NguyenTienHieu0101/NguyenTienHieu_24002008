<?php
    $host = "127.0.0.1:3307";
    $dbname = "shopping_cart";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",
            $username,
            $password
        );

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo("Kết nối database thất bại: <br>" . $e->getMessage());
        //  die("Kết nối database thất bại: " . $e->getMessage());
        exit;
    }
?>