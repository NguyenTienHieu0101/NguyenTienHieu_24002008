CREATE DATABASE IF NOT EXISTS products_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE products_db;

CREATE TABLE products (
    id    INT          NOT NULL AUTO_INCREMENT,
    name  VARCHAR(100) NOT NULL,                   
    price INT          NOT NULL,                  
    stock INT          NOT NULL,                 
    image VARCHAR(255) NULL,
    PRIMARY KEY (id),
    CONSTRAINT chk_price CHECK (price > 0),
    CONSTRAINT chk_stock CHECK (stock >= 0)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Dữ liệu mẫu
INSERT INTO products (name, price, stock) VALUES
    ('Bút bi',   5000, 20),
    ('Vở ô ly', 12000, 15),
    ('Thước kẻ', 8000,  0),
    ('Tẩy trắng', 3000, 30),
    ('Bút chì', 4000, 25),
    ('Compa', 18000, 8),
    ('Hộp bút', 35000, 12),
    ('Sổ tay', 22000, 18),
    ('Kéo học sinh', 14000, 10),
    ('Bút màu', 45000, 6),
    ('Giấy kiểm tra', 7000, 40),
    ('Balo học sinh', 250000, 4);