CREATE DATABASE IF NOT EXISTS patched_db;
USE patched_db;


DESCRIBE news;

-- Create the `news` table
CREATE TABLE IF NOT EXISTS news (
                                  id INT AUTO_INCREMENT PRIMARY KEY,
                                  title VARCHAR(255) NOT NULL,
                                  content TEXT NOT NULL,
                                  url VARCHAR(255) NOT NULL,
                                  published_at DATETIME NOT NULL,
                                  category VARCHAR(50) NOT NULL,
                                  image_url VARCHAR(255),
                                  full_content TEXT,
                                  UNIQUE KEY unique_news (title, url, published_at)
);

-- Create the `users` table
CREATE TABLE IF NOT EXISTS users (
                                   id INT AUTO_INCREMENT PRIMARY KEY,
                                   username VARCHAR(50) NOT NULL,
                                   password VARCHAR(255) NOT NULL,
                                   role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
                                   UNIQUE KEY unique_username (username)
);
