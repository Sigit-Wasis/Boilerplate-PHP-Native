CREATE DATABASE IF NOT EXISTS boilerplate;
USE boilerplate;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    username VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(225),
    alamat VARCHAR(50),
    foto VARCHAR (50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    caption TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Table Users
-- password hash for 'password123'
-- $2a$12$j65tQgBdR1yXZyckjnglwuLM9Xl.r1SHLHLkXzcplUlZvlAPa9252
INSERT INTO users (username, nama_lengkap, email, password, alamat, foto) VALUES
('admin', 'Administrator', 'B0J0q@example.com', '$2a$12$j65tQgBdR1yXZyckjnglwuLM9Xl.r1SHLHLkXzcplUlZvlAPa9252', 'setiabudi', 'https://i.ibb.co/3k3yT7Q/avatar.jpg'),
('johndoe', 'John Doe', 'zZGd0@example.com', '$2a$12$j65tQgBdR1yXZyckjnglwuLM9Xl.r1SHLHLkXzcplUlZvlAPa9252', 'setiabudi', 'https://i.ibb.co/3k3yT7Q/avatar.jpg');

-- Insert Table Posts
INSERT INTO posts (user_id, caption, image) VALUES
(1, 'Ini adalah postingan pertama saya!', 'https://i.ibb.co/2kR8V7W/post1.jpg'),
(2, 'Senang bisa bergabung di sini!', 'https://i.ibb.co/2kR8V7W/post2.jpg');