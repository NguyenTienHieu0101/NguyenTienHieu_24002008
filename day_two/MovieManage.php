<?php
///  Class lưu đối tượng Movie
class Movie{
    public string $id;  // mã phim
    public string $title;  // tên phim
    public float $price; // giá vé
    public int $totalSeats;  // tổng số ghế
    public int $availableSeats;  // số ghế còn lại

    // HÀM DỰNG CÓ THAM SỐ
    public function __construct(string $id, string $title, float $price, int $totalSeats){
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }

    // hàm đặt vé 
    public function bookTicket(int $quantity){
        if ($quantity <= 0){
            echo "Số lượng vé không hợp lệ! <br>";
            return;
        }
        if ($this->availableSeats == 0){
            echo "Đã hết ghế! <br>";
            return;
        }
        if ($quantity > $this->availableSeats){
            echo "Số ghế còn lại không đủ, hãy giảm số ghế đặt! <br>";
            return;
        }
        $this->availableSeats -= $quantity;
        echo "Đã đặt " . $quantity . " vé. <br>";
    }

    // hàm hủy số lượng vé đã đặt
    public function cancelTicket(int $quantity){
        if ($quantity <= 0){
            echo "Không hợp lệ!";
            return;
        }
        // số ghế đã đặt
        $bookedSeats = $this->getSoldSeats();
        if ($quantity > $bookedSeats){
            echo "Số lượng hủy lớn hơn số đã có, nhập lại!";
            return;
        }
        $this->availableSeats += $quantity;
        echo "Đã hủy " . $quantity . " vé. <br>";
    }

    // hàm Trả về số vé đã bán theo công thức: totalSeats - availableSeats.
    public function getSoldSeats(){
        return $this->totalSeats - $this->availableSeats;
    }

    // Trả về doanh thu của phim theo công thức: số vé đã bán × price.
    public function getRevenue(){
        return $this->getSoldSeats() * $this->price;
    }

    // Hiển thị mã phim, tên phim, giá vé, tổng số ghế, số ghế còn lại, số vé đã bán và doanh thu.
    public function displayInfo(){
        echo "Mã phim: ". $this->id ." | Tên phim: ". $this->title . " | Giá vé: ". $this->price . 
            " | Tổng số ghế: ". $this->totalSeats . " | Số ghế còn lại: ". $this->availableSeats .
            " | Số vé đã bán: ". $this->getSoldSeats() ." | Doanh thu: ". $this->getRevenue() . "<br>";
    }
}
function display(array $movies){
    foreach ($movies as $ms){
        $ms->displayInfo();
    }
}
function findMovieById(array $movies, string $id){
    if ($movies == null){
        return null;
    }
    if (strlen($id) == 0 || empty($id)){
        return null;
    }
    foreach ($movies as $ms){
        if ($ms->id == $id){
            return $ms;
        }
    }
    return null;
}
function getTotalRevenue(array $movies){
    if ($movies == null) return 0;
    $total = 0;
    foreach ($movies as $ms){
        $total += $ms->getRevenue();
    }
    return $total;
}
function getBestSellingMovie(array $movies){
    if ($movies == null) return null;
    $movie = $movies[0];
    $maxSell = $movie->getSoldSeats();
    foreach ($movies as $ms){
        if ($maxSell < $ms->getSoldSeats()){
            $movie = $ms;
            $maxSell = $ms->getSoldSeats();
        }
    }
    return $movie;
}

///  hàm main
function main(){
    $movies = [];
    $movies[] = new Movie("01010", "Anh yêu em", 50000, 70);
    $movies[] = new Movie("01110", "Anh nhớ em", 80000, 60);
    $movies[] = new Movie("01330", "Chia tay", 100000, 50);
    $movies[] = new Movie("04000", "Chỉ là quá khứ", 55000, 70);
    $movies[] = new Movie("02432", "Nhớ em", 90000, 75);

    // hiển thị danh sách
    display($movies);
    echo "<br>";


    // hàm tìm phim theo ID và trả về object Movie tương ứng. Nếu không tìm thấy, trả về null.
    $movie = findMovieById($movies, "01110");  
    echo " Tìm theo Id == 01110 (có tồn tại) :<br>";
    if ($movie != null){
        $movie->displayInfo();
    } else {
        echo " Id không tồn tại <br>";
    }
    echo "<br>";
    $movie = findMovieById($movies, "09110");  
    echo " Tìm theo Id == 09110 (không tồn tại) :<br>";
    if ($movie != null){
        $movie->displayInfo();
    } else {
        echo " Id không tồn tại <br>";
    }
    echo "<br>";

    // mua vé
    $movies[0]->bookTicket(20);
    echo "<br>";
    display($movies);

    // hàm tính tổng doanh thư tất cả các phim
    $total = getTotalRevenue($movies);
    echo "Tổng doanh thu :" . $total ." đ<br>";
    echo "<br>";

    // Tìm phim có số vé đã bán nhiều nhất và trả về object Movie tương ứng.
    $bestMovie = getBestSellingMovie($movies);
    echo "Phim bán chạy nhất:<br>";
    if ($bestMovie != null){
        $bestMovie->displayInfo();
    } else {
        echo "không tồn tại <br>";
    }
    echo "<br>";

}
main();

?>

<!-- Mã phim: 01010 | Tên phim: Anh yêu em | Giá vé: 50000 | Tổng số ghế: 70 | Số ghế còn lại: 70 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 01110 | Tên phim: Anh nhớ em | Giá vé: 80000 | Tổng số ghế: 60 | Số ghế còn lại: 60 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 01330 | Tên phim: Chia tay | Giá vé: 100000 | Tổng số ghế: 50 | Số ghế còn lại: 50 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 04000 | Tên phim: Chỉ là quá khứ | Giá vé: 55000 | Tổng số ghế: 70 | Số ghế còn lại: 70 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 02432 | Tên phim: Nhớ em | Giá vé: 90000 | Tổng số ghế: 75 | Số ghế còn lại: 75 | Số vé đã bán: 0 | Doanh thu: 0

Tìm theo Id == 01110 (có tồn tại) :
Mã phim: 01110 | Tên phim: Anh nhớ em | Giá vé: 80000 | Tổng số ghế: 60 | Số ghế còn lại: 60 | Số vé đã bán: 0 | Doanh thu: 0

Tìm theo Id == 09110 (không tồn tại) :
Id không tồn tại

Đã đặt 20 vé.

Mã phim: 01010 | Tên phim: Anh yêu em | Giá vé: 50000 | Tổng số ghế: 70 | Số ghế còn lại: 50 | Số vé đã bán: 20 | Doanh thu: 1000000
Mã phim: 01110 | Tên phim: Anh nhớ em | Giá vé: 80000 | Tổng số ghế: 60 | Số ghế còn lại: 60 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 01330 | Tên phim: Chia tay | Giá vé: 100000 | Tổng số ghế: 50 | Số ghế còn lại: 50 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 04000 | Tên phim: Chỉ là quá khứ | Giá vé: 55000 | Tổng số ghế: 70 | Số ghế còn lại: 70 | Số vé đã bán: 0 | Doanh thu: 0
Mã phim: 02432 | Tên phim: Nhớ em | Giá vé: 90000 | Tổng số ghế: 75 | Số ghế còn lại: 75 | Số vé đã bán: 0 | Doanh thu: 0
Tổng doanh thu :1000000 đ

Phim bán chạy nhất:
Mã phim: 01010 | Tên phim: Anh yêu em | Giá vé: 50000 | Tổng số ghế: 70 | Số ghế còn lại: 50 | Số vé đã bán: 20 | Doanh thu: 1000000 -->