CREATE DATABASE web_app;
USE web_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    priority INT NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@example.com', '$2y$10$J8Z7z3Y5X8Z9Y2X4Z6Y8Z.QZ7Z3Y5X8Z9Y2X4Z6Y8Z', 'admin');
-- Password for admin is 'admin123'