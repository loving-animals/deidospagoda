CREATE DATABASE IF NOT EXISTS DeyDospagoda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DeyDospagoda;

-- 1. USERS & ROLES TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NULL, -- Nullable for Google OAuth users
    google_id VARCHAR(255) NULL UNIQUE,
    role ENUM('Admin', 'Permission User', 'Normal User') DEFAULT 'Normal User',
    assigned_page VARCHAR(50) DEFAULT NULL, -- For Permission Users: 'monks', 'adsen', 'car', 'study'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. MONKS TABLE (ចំនួនព្រះសង្ឃ)
CREATE TABLE monks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    chhaya VARCHAR(100) NOT NULL UNIQUE,
    birthplace TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ADSEN MONK TABLE (ព្រះសង្ឃមិនទើងឆាន់)
CREATE TABLE adsen_monk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    qty INT DEFAULT 1,
    money DECIMAL(12, 2) DEFAULT 5000.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. CAR PAYMENT TABLE (ព្រះសង្ឃបង់ថ្លៃឡាន)
CREATE TABLE car_monk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    month_record VARCHAR(20) NOT NULL, -- Format: YYYY-MM
    money DECIMAL(12, 2) DEFAULT 45000.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_monk_month (name, month_record)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. STUDY ABSENT TABLE (ព្រះសង្ឃអវត្តមានរៀន)
CREATE TABLE study_monk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    qty INT DEFAULT 1,
    money DECIMAL(12, 2) DEFAULT 10000.00,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. FESTIVAL NEWS TABLE
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT 'default_news.jpg',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. MESSAGES TABLE
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default Admin account (Password: Admin@DeyDos2026)
INSERT INTO users (name, email, password, role) VALUES 
('ព្រះគ្រូចៅអធិការ', 'admin@deydospagoda.gov.kh', '$2y$10$wK1.T9Zf7oBvN3Vp2mC/1O3b5aYwz3gRbxvR7MhQ6C6S.E2FmX5L.', 'Admin');