CREATE DATABASE IF NOT EXISTS boilerplate;
USE boilerplate;

<<<<<<< HEAD

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    username VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(225),
    alamat VARCHAR(50) UNIQUE,
    foto VARCHAR (50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATED TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    caption TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATED TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_ INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATED TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATED TABLE follows(
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    following_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATED TABLE pv_post_image(
    post_id INT,
    media_id INT,
    PRIMARY KEY (post_id, media_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE

);

 CREATED TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file VARCHAR(255),
    type_file VARCHAR(50),
    size INT,
    resolution VARCHAR(20)
 );

)
)


=======
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CREATE TABLE JABATAN
CREATE TABLE IF NOT EXISTS jabatan (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERT TABLE USERS MINIMAL 5 DATA
INSERT INTO users (name, email) VALUES
('Alice Johnson', 'xH6kF@example.com'),
('Bob Smith', 'TbPw2@example.com'),
('Charlie Brown', 'zDc6d@example.com'),
('Diana Prince', 'vH1ZV@example.com'),
('Ethan Hunt', 'bD2dH@example.com');

-- INSERT TABLE JABATAN MINIMAL 5 DATA
INSERT INTO jabatan (nama_jabatan) VALUES
('Manager'),
('Supervisor'),
('Staff'),
('Intern'),
('Director');
>>>>>>> 1e01a3f506a160359ed9cc95849f612d7a575c4a
