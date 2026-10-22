<?php
    function getRank($score){
        if ($score < 0 || $score > 10){
            return "Không xếp hạng!";
        }
        $loai = "";
        if ($score < 5){
            return $loai = "Yếu";
        } elseif ($score < 6.5){
            return $loai = "Trung bình";
        } elseif ($score < 8){
            return $loai = "Khá";
        } elseif ($score >= 8){
            return $loai = "Giỏi";
        }
    }


    $name1 = "Nguyen Tien Hieu";
    $score1 = 9;
    $resultrank = getRank($score1);
    echo "Name: " . $name1 . "<br>";
    echo  $score1 . " Rank: " . $resultrank . "<br>";


    $name2 = "Nguyen Minh Hieu";
    $score2 = 4;
    $resultrank1 = getRank($score2);
    echo "Name: " . $name2 . "<br>";
    echo $score2 . " Rank: " . $resultrank1 . "<br>";

    $name3 = "Nguyen Thi Hieu";
    $score3 = 7;
    $resultrank2 = getRank($score3);
    echo "Name: " . $name3 . "<br>";
    echo $score3 . " Rank: " . $resultrank2 . "<br>";

    $name = "Nguyen Van Hieu";
    $score = 6;
    $resultrank3 = getRank($score);
    echo "Name: " . $name . "<br>";
    echo $score . " Rank: " . $resultrank3 . "<br>";

    $name4 = "Nguyen Van Hieu";
    $score4 = 11;
    $resultrank4 = getRank($score4);
    echo "Name: " . $name4 . "<br>";
    echo $score4 . " Rank: " . $resultrank4 . "<br>";
?>
