CREATE DATABASE IF NOT EXISTS crud_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE crud_app;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, price, description) VALUES
  ('Wireless Mouse', 19.99, 'Ergonomic wireless mouse with 2.4GHz receiver'),
  ('Mechanical Keyboard', 79.50, 'RGB backlit mechanical keyboard with blue switches'),
  ('USB-C Hub', 34.00, '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader');
