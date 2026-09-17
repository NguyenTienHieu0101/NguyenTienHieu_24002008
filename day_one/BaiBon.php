<?php
class Student{
    public string $name;
    public int $age;
    public float $score;
    public function __construct(string $name, int $age, float $score){
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }
    public function getRank(){
        $loai = "";
        if ($this->score < 5){
            $loai = "Yếu";
        } elseif ($this->score < 6.5){
            $loai = "Trung bình";
        } elseif ($this->score < 8){
            $loai = "Khá";
        } elseif ($this->score >= 8){
            $loai = "Giỏi";
        }
        return $loai;
    }
    public function isPassed() {
        if ($this->score >= 5){
            return true;
        }
        return false;
    }
    public function display() {
        echo "Name : " . $this->name . ", Age = " . $this->age . ", Score = " . $this->score . "\n";
    }
}

class StudentManage{
    public array $students;
    function __construct() {
        $this->students = [];
    }
    public function addStudent(Student $student) {
        $this->students[] = $student;
    }

    public function findBestStudent(){
        $maxScore = 0;
        $result = [];
        foreach ($this->students as $st){
            if ($st->score > $maxScore){
                $maxScore = $st->score;
            }
        }
        foreach ($this->students as $st){
            if ($st->score == $maxScore){
                $result[] = $st;
            }
        }
        return $result;
    }


    public function countPassedStudents(){
        $count = 0;
        foreach ($this->students as $st){
            if ($st->score >= 5){
                $count += 1;
            }
        }
        return $count;
    }

    public function calculateAverageScore(){
        $avg = 0;
        foreach ($this->students as $st){
            $avg += $st->score;
        }
        return $avg/count($this->students);
    }
    public function displayStudent() {
        foreach ($this->students as $st){
            $st->display();
        }
    }
}

$student1 = new Student("Nguyen Van A", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

$students = new StudentManage();
$students->addStudent($student1);
$students->addStudent($student2);
$students->addStudent($student3);
$students->addStudent($student4);
# duyệt danh sách
$students->displayStudent();

# tìm sinh viên max điểm
function displayStudent(array $students) {
    foreach ($students as $st){
        $st->display();
    }
}
$maxScoreST = $students->findBestStudent();
echo "Sinh viên điểm cao nhất \n";
displayStudent($maxScoreST);

# đếm số sinh viên đạt
$dat = $students->countPassedStudents();
echo "Đạt : " . $dat . "\n";

# Điểm trung bình
$avgScore = $students->calculateAverageScore();
echo "Avg : " . $avgScore;
?>