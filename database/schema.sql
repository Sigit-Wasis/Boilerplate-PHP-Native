CREATE DATABASE IF NOT EXISTS boilerplate;
USE boilerplate;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    alamat VARCHAR(50) UNIQUE,
    foto VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)REFERENCES users (id) ON DeLETE CASCADE
);


CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id)REFERENCES posts (id) ON DeLETE CASCADE,
    FOREIGN KEY (user_id)REFERENCES users (id) ON DeLETE CASCADE
);

CREATE TABLE comments(
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    coment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id)REFERENCES posts (id) ON DeLETE CASCADE,
    FOREIGN KEY (user_id)REFERENCES users (id) ON DeLETE CASCADE  
);

CREATE TABLE follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    following_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id)REFERENCES posts (id) ON DeLETE CASCADE,
    FOREIGN KEY (following_id)REFERENCES users (id) ON DeLETE CASCADE  
);

CREATE TABLE pv_post_image(
    post_id INT,
    media_id INT
    PRIMARY KEY (post_id, media_id),
    FOREIGN KEY (post_id)REFERENCES posts (id) ON DeLETE CASCADE,
    FOREIGN KEY (media_id)REFERENCES media (id) ON DeLETE CASCADE  

);

CREATE TABLE media(
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    type_file VARCHAR(50),
    size INT,
    resolution VARCHAR (20)
);
    
    CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id)
);