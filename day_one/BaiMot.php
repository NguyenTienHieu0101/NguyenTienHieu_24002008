<?php
    $students = [
        ["name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5],
        ["name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5],
        ["name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5],
        ["name" => "Pham Thi Dung",
        "age" => 23,
        "score" => 7.5]
    ];
    // in danh sách
    foreach ($students as $st){
        echo "name : " . $st["name"] . ", age : " . $st["age"] . ", score : " . $st["score"] . "\n";
    }
    // tính trung bình cộng điểm
    $avg = 0;
    foreach ($students as $st){
        $avg += $st["score"];
    }
    echo "Điểm trung bình : " . $avg/4;
?>
