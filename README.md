# Russian News Website Project

## Overview
This project involves the development of a fully functional website for presenting Russian news. The site fetches real-time news data from Russian news sources using RSS feeds. The website features dynamic content and provides users the ability to search for news articles.

Additionally, this project includes the implementation of several vulnerabilities for learning and testing purposes, alongside their patched counterparts to demonstrate secure coding practices.

## Features
- Displays recent Russian news dynamically (via RSS feeds).
- Search functionality for finding specific news articles.
- Multilingual support (English or Russian).
- Vulnerabilities implemented on specific pages for educational purposes.
- Secure (patched) versions of the application for comparison.
- A user-friendly graphical interface for seamless interaction.

## Vulnerabilities Implemented
Each vulnerability has been implemented on a separate PHP page:

1. **Reflected Cross-Site Scripting (XSS)**:
   - Exploitable via dynamic HTML content injection.
   - Found in `vulnerabilities/reflected_xss.php`.

2. **SQL Injection (1)**:
   - Basic SQL injection targeting login or search functionality.
   - Found in `vulnerabilities/sql_injection_1.php`.

3. **SQL Injection (2)**:
   - Advanced SQL injection exploiting multi-query execution.
   - Found in `vulnerabilities/sql_injection_2.php`.

4. **Path Traversal (CWE-35)**:
   - Allows unauthorized file access using traversal sequences (e.g., `.../...//`).
   - Found in `vulnerabilities/path_traversal.php`.

5. **Server-Side Request Forgery (SSRF)**:
   - Exploitable SSRF with a blacklist-based input filter.
   - Found in `vulnerabilities/ssrf.php`.

6. **Unrestricted File Upload (CWE-434)**:
   - Blacklist-based prevention bypass for dangerous file uploads.
   - Found in `vulnerabilities/unrestricted_file_upload.php`.

7. **Blind OS Command Injection**:
   - Exploits user input to execute OS commands without output feedback.
   - Found in `vulnerabilities/blind_os_command_injection.php`.

## Secure Version
A secure version of the website is also provided. The patched files are located in the `patched/` directory. These include:
- Sanitization and validation of inputs.
- Proper use of parameterized queries and prepared statements for database interactions.
- Secure file upload restrictions (whitelisting and MIME-type validation).
- Mitigation for XSS, SSRF, and other vulnerabilities.

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/russian-news-website.git
   cd russian-news-website
   ```

2. **Set Up Environment**:
   - Install and configure a local server (e.g., XAMPP, WAMP, or LAMP).
   - Place the repository folder in the server’s root directory (e.g., `htdocs` for XAMPP).

3. **Database Configuration**:
   - Import the SQL file located in `database/setup.sql` to create the required database and tables.
   - Update the database credentials in `config/db.php`.

4. **Run the Application**:
   - Start the server and navigate to `http://localhost/russian-news-website` in your browser.

## Usage
- **Dynamic News Content**: Visit the homepage to view the latest Russian news fetched via RSS feeds.
- **Search**: Use the search bar to find specific news articles.
- **Testing Vulnerabilities**: Access individual vulnerability pages via the `vulnerabilities/` directory.
- **Secure Version**: Access the secure version via the `patched/` directory.


## Security Measures in Patched Version
- **Reflected XSS**: Escaping output and validating input.
- **SQL Injection**: Use of parameterized queries with prepared statements.
- **Path Traversal**: Normalizing paths and restricting file access.
- **SSRF**: Validation of URLs and implementation of a whitelist.
- **Unrestricted File Upload**: MIME type and file extension whitelisting.
- **Blind OS Command Injection**: Sanitization of input and limiting system commands.

## Technologies Used
- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Others**: RSS feed processing libraries



