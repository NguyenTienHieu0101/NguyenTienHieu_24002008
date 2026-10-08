<?php
require_once __DIR__ . "/../common/dbConnect.php";
// require "app/common/dbConnect.php";

function getAllProducts(){
    global $conn;
    $query = "SELECT * FROM cart_items;";
    $sql = $conn->query($query);
    return $sql->fetchAll(PDO::FETCH_ASSOC);  // MỘT SET
}

function getProductById(int $id){
    global $conn;
    $query = "SELECT * FROM cart_items WHERE id = :id;";
    $sql = $conn->prepare($query);
    $sql->execute(["id" => $id]);  // Tránh chuyền id trực tiếp vào query
    return $sql->fetch(PDO::FETCH_ASSOC);
}

function addProduct(string $name, float $price, int $quantity){
    global $conn;
    $query = " INSERT INTO cart_items (name, price, quantity)
                VALUES (:name, :price, :quantity);";
    $sql = $conn->prepare($query);
    // true / false
    return $sql->execute(["name" => $name, "price" => $price, "quantity"=>$quantity]); 
}

function updateProduct(int $id, string $name, float $price, int $quantity){
    global $conn;
    $query = "UPDATE cart_items
                SET name = :name, price = :price, quantity = :quantity
                WHERE id = :id;";
    $sql = $conn->prepare($query);
    return $sql->execute(["id" => $id, "name" => $name, "price" => $price, "quantity"=>$quantity]); 
}

function deleteProduct(int $id){
    global $conn;
    $query = "DELETE FROM cart_items WHERE id = :id;";
    $sql = $conn->prepare($query);
    return $sql->execute(["id" => $id]); 
}
?>