<?php
    function getRank(array $student){
        $result = [];
        foreach ($student as $st){
            $loai = "";
            if ($st["score"] < 5){
                $loai = "Yếu";
            } elseif ($st["score"] < 6.5){
                $loai = "Trung bình";
            } elseif ($st["score"] < 8){
                $loai = "Khá";
            } elseif ($st["score"] >= 8){
                $loai = "Giỏi";
            }
            $result[] = ["name" => $st["name"], "age" => $st["age"],"score" => $st["score"], "rank" => $loai ];
        }
        return $result;
    }
    function calculateAverageScore(array $student){
        $avg = 0;
        foreach ($student as $st){
            $avg += $st["score"];
        }
        return $avg/count($student);
    }
    function displayStudent(array $student){
        foreach ($student as $st){
            echo "name : " . $st["name"] . ", age : " . $st["age"] . ", score : " . $st["score"] . ", rank : " . $st["rank"] . "\n";
        }
    }


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


    // tính trung bình cộng điểm
    $avg = calculateAverageScore($students);
    echo "Điểm trung bình : $avg \n";
    $resultrank = getRank($students);
    displayStudent($resultrank);
?>


