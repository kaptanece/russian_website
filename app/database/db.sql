CREATE DATABASE IF NOT EXISTS php_project;
USE php_project;


CREATE TABLE news (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    content TEXT NOT NULL,
                    url VARCHAR(255) NOT NULL,
                    published_at DATETIME NOT NULL
);

CREATE TABLE users (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     username VARCHAR(50),
                     password VARCHAR(255)
);

INSERT INTO users (username, password) VALUES ('admin', 'password123');
