USE products_db;

ALTER TABLE products
    ADD COLUMN image VARCHAR(255) NULL;

INSERT INTO products (name, price, stock) VALUES
    ('Tẩy trắng', 3000, 30),
    ('Bút chì', 4000, 25),
    ('Compa', 18000, 8),
    ('Hộp bút', 35000, 12),
    ('Sổ tay', 22000, 18),
    ('Kéo học sinh', 14000, 10),
    ('Bút màu', 45000, 6),
    ('Giấy kiểm tra', 7000, 40),
    ('Balo học sinh', 250000, 4);
