CREATE DATABASE IF NOT EXISTS boilerplate;
USE boilerplate;

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
