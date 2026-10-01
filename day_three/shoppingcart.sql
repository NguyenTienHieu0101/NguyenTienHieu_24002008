CREATE DATABASE shopping_cart
       CHARACTER SET utf8mb4
       COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

CREATE TABLE cart_items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO cart_items VALUES
(1, "Thịt chó", 150000.0, 3),
(2, "Giềng", 7000, 1),
(3, "Mắm tôm", 20000, 1),
(4, "Lá mơ", 25000, 1),
(5, "Đu đủ", 30000, 1);

-- Hiển thị sản phẩm
SELECT * FROM cart_items;

-- Hiển thị sản phẩm có giá > 100000
SELECT * FROM cart_items
WHERE price > 100000;

-- Hiển thị sản phẩm có số lượng lớn hơn 5
SELECT * FROM cart_items
WHERE quantity > 5;

-- Sắp xép theo giá giảm dần
SELECT * FROM cart_items
ORDER BY price DESC;

-- Cập nhật giá "Lá mơ"
UPDATE cart_items
SET price = 20000
-- WHERE name = "Lá mơ";
WHERE id = 4; 

-- Cập nhật số lượng của 1 sản phẩm (Đu đủ)
UPDATE cart_items
SET quantity = 2
-- WHERE name = "Đu đủ";
WHERE id = 5; 

-- Xóa một sản phẩm
DELETE FROM cart_items
-- WHERE name = "Đu đủ";
WHERE id = 5; 

-- Hiển thị: tên , giá, số lượng, thành tiền (price * quantity)
SELECT name AS "Tên", price AS "Giá", quantity AS "Số lượng", (price * quantity) AS "Thành tiền"
FROM cart_items;

-- Tính tổng tiền
SELECT SUM(price * quantity) AS "Tổng tiền"
FROM cart_items;