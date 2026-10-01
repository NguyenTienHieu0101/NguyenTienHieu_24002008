CREATE DATABASE movie_trending;
       CHARACTER SET utf8mb4
       COLLATE utf8mb4_unicode_ci;

USE movie_trending;

CREATE TABLE movies(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);
-- Thêm phim
INSERT INTO movies VALUES
(1, "Anh yêu em", 90000.0, 80, 80),
(2, "Anh nhớ em", 70000, 85, 85),
(3, "Chia tay", 85000, 60, 60),
(4, "Hạnh phúc chính là em", 105000, 60, 60),
(5, "Anh không thứ tha", 100000, 50, 50);

-- Hiển thị phim
SELECT * FROM movies;

-- Hiển thị phim có giá > 100000
SELECT * FROM movies
WHERE price > 100000;

-- Hiển thị phim còn nhiều hơn 50 ghế
SELECT * FROM movies
WHERE available_seats >= 50;

-- Sắp xép theo giá giảm dần
SELECT * FROM movies
ORDER BY price DESC;

-- Cập nhật số ghế còn lại của 1 phim (Anh yêu em)
UPDATE movies
SET available_seats = 49
-- WHERE title = "Anh yêu em";
WHERE id = 1; 

-- Xóa một sản phẩm (Anh yêu em)
DELETE FROM movies
-- WHERE title = "Anh yêu em";
WHERE id = 1; 

-- Hiển thị: số vé đã bán của từng phim
SELECT title AS "Tên phim", (total_seats - available_seats) AS "Số vé đã bán"
FROM movies;

-- Tính doanh thu từng phim
SELECT title AS "Tên phim", (total_seats - available_seats)*price AS "Doanh thu"
FROM movies;

-- Tính tổng doanh thu các phim
SELECT  SUM((total_seats - available_seats)*price) AS "Tổng Doanh thu"
FROM movies;

-- Tìm phim có tổng doanh thu nhiều nhất
SELECT title AS "Phim có doanh thu cao nhất"
FROM movies
WHERE (total_seats - available_seats)*price == (
	SELECT MAX((total_seats - available_seats)*price)
	FROM movies
);
