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

-- Alter the users table to add a role column
ALTER TABLE users
  ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user';

-- Update existing users to assign the "user" role
UPDATE users SET role = 'user' WHERE username != 'admin';

-- Insert the admin user with the admin role
INSERT INTO users (username, password, role) VALUES
  ('admin', 'adminpass', 'admin');

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


DELETE FROM users
WHERE id != 1 AND username = 'admin';
ALTER TABLE users ADD CONSTRAINT unique_username UNIQUE (username);

SELECT * FROM news WHERE 1=1 ORDER BY published_at DESC;
ALTER TABLE news ADD CONSTRAINT unique_news UNIQUE (title, url, published_at);

SELECT * FROM news
WHERE 1=1
  AND (title LIKE '%%' OR 1=1 --
  OR content LIKE '%%')
ORDER BY published_at DESC;

SELECT * FROM news WHERE 1=1 AND (title LIKE '%') OR 1=1; -- ) ORDER BY published_at DESC;

SELECT * FROM news WHERE 1=1 AND (title LIKE '%test%' OR content LIKE '%test%');

SELECT @@sql_mode;
SET sql_mode = '';

-- SELECT * FROM news WHERE 1=1 AND title = '%' UNION SELECT NULL, NULL, NULL, NULL, NULL -- '

 -- SELECT * FROM news WHERE 1=1 AND (title LIKE '%%' OR 1=1 --%' OR content LIKE '%%' OR 1=1 --%')

SELECT * FROM news WHERE 1=1 AND title LIKE '%Technology%' ORDER BY published_at DESC;


SELECT * FROM news WHERE 1=1 AND (title LIKE '%% OR 1=1 -- %' OR content LIKE '%% OR 1=1 -- %') ORDER BY published_at DESC

INSERT INTO users (username, password, role)
VALUES ('adminecem', 'password123', 'admin');

