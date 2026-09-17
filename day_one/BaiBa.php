<?php 
    function findBestStudent(array $student){
        $maxScore = 0;
        $result = [];
        foreach ($student as $st){
            if ($st["score"] > $maxScore){
                $maxScore = $st["score"];
            }
        }
        foreach ($student as $st){
            if ($st["score"] == $maxScore){
                $result[] = $st;
            }
        }
        return $result;
    }
    function findWorstStudent(array $student){
        $minScore = 12;
        $result = [];
        foreach ($student as $st){
            if ($st["score"] < $minScore){
                $minScore = $st["score"];
            }
        }
        foreach ($student as $st){
            if ($st["score"] == $minScore){
                $result[] = $st;
            }
        }
        return $result;
    }
    function countPassedStudents(array $student){
        $count = 0;
        foreach ($student as $st){
            if ($st["score"] >= 5){
                $count += 1;
            }
        }
        return $count;
    }
    function findStudentByName(array $student, string $name) {
        foreach ($student as $st){
            if ($st["name"] == $name){
                return $st;
            }
        }
        return [];
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

    $a = findBestStudent($students);
    echo "tốt nhất ". count($a) ."\n";
    $b = findWorstStudent($students);
    echo "kém nhất " . count($b) ."\n";
    $c = countPassedStudents($students);
    echo "qua môn $c \n";
    $d = findStudentByName($students, "Tran Thi Binh");
    if (count($d) != 0){
        echo "điểm của Tran Thi Binh là " .  $d["score"]  . "\n";
    }
    
?>