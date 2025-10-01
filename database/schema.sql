CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(225),
    alamat VARCHAR(50) UNIQUE,
    foto VARCHAR (50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE follows(
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    following_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    type_file VARCHAR(50),
    size INT,
    resolution VARCHAR(20)
);

CREATE TABLE pv_post_image (
    post_id INT,
    media_id INT,
    PRIMARY KEY (post_id, media_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
);

INSERT INTO users (username, nama_lengkap, email, password, alamat, foto) VALUES
('sigit', 'Sigit Wasis Subekti', 'Lrj0Q@example.com', '$2b$12$iF6UWdOb2vNREq5xZS2E/.2m/uydJUG4I8JIE.8bOt91SZ7W0AMFy', 'Jl. Sudirman No. 123', 'sigit.jpg'),
('joko', 'Joko Widodo', 'h2p8k@example.com', '$2b$12$iF6UWdOb2vNREq5xZS2E/.2m/uydJUG4I8JIE.8bOt91SZ7W0AMFy', 'Jl. Sudirman No. 456', 'joko.jpg'),
('budi', 'Budi Santoso', 'Tq1yI@example.com', '$2b$12$iF6UWdOb2vNREq5xZS2E/.2m/uydJUG4I8JIE.8bOt91SZ7W0AMFy', 'Jl. Sudirman No. 789', 'budi.jpg');

INSERT INTO posts (user_id, caption) VALUES
(1, 'Ini adalah postingan pertama saya!'),
(2, 'Selamat pagi semuanya!'),
(3, 'Hari ini cuacanya cerah.'),
(1, 'Saya suka belajar pemrograman.'),
(2, 'Liburan ke pantai sangat menyenangkan!');

INSERT INTO likes (post_id, user_id) VALUES
(1, 2),
(1, 3),
(2, 1),
(3, 1),
(3, 2),
(4, 2),
(5, 1);
