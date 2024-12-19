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

SELECT * FROM news;

ALTER TABLE news
  ADD COLUMN category VARCHAR(50) NOT NULL AFTER content;


SELECT id, title, url, COUNT(*) as count
FROM news
GROUP BY title, url
HAVING count > 1;

ALTER TABLE news ADD UNIQUE (title, url);

ALTER TABLE news ADD COLUMN image_url VARCHAR(255);
ALTER TABLE news ADD COLUMN full_content TEXT;
ALTER TABLE news ADD COLUMN categories TEXT;

ALTER TABLE news DROP COLUMN categories;
