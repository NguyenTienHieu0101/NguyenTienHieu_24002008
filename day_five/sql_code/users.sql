USE shopping_cart;

CREATE TABLE cart_items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
);

-- MẪU THỬ NHIỆM
-- 1
-- Nguyễn Tiến Hiếu
-- $2y$10$4Mgb1iQTF8hOjkvOrOhndevJAuBKWHc7.H8uOASoJbz...  (123456 -> QUA HASH CODE  -> OASoJbz23fA7VKNAi)