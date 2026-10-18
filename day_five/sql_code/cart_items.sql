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