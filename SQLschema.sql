CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Unique user ID
    firstname VARCHAR(50) NOT NULL,              -- User's first name
    lastname VARCHAR(50) NOT NULL,               -- User's last name
    email VARCHAR(100) NOT NULL UNIQUE,          -- User's email address (unique)
    password VARCHAR(255) NOT NULL,              -- Hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Account creation timestamp
    reset_token VARCHAR(64) DEFAULT NULL,        -- Token for password reset
    token_expiry DATETIME DEFAULT NULL           -- Expiry for password reset token
);
