<?php
    function findMaxPrice(array $products){
        if (count($products) == 0) return 0;
        $maxPrice = 0;
        foreach ($products as $st){
            if ($st["price"] > $maxPrice){
                $maxPrice = $st["price"];
            }
        }
        return $maxPrice;
    }
    function calculateTotal(array $products){
        if (count($products) == 0) return 0;
        $maxScore = 0;
        $result = 0;
        foreach ($products as $st){
            $result += $st["price"];
        }
        return $result;
    }
    function displayStudent(array $products){
        if (count($products) == 0) return;
        foreach ($products as $st){
            echo "name : " . $st["name"] . " | giá : " . $st["price"] .  "đ <br>";
        }
    }

$products = [
    ["name" => "A", "price" => 30000],
    ["name" => "B", "price" => 35000],
    ["name" => "C", "price" => 40000],
    ["name" => "D", "price" => 340000],
    ["name" => "E", "price" => 6000],
    ["name" => "F", "price" => 70000],
];

// danh sách sản phẩm
displayStudent($products);


// TÍNH TỔNG
$sum = calculateTotal($products);
echo "Tổng là: " . $sum ."đ <br>";


// tìm giá max
$maxPrice = findMaxPrice($products);
echo "Giá max  là: " . $maxPrice ."đ <br>";

?>
