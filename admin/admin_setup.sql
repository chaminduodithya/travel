-- ═══════════════════════════════════════════════════════════════════════════
-- travel. Admin — Database Setup Script
-- Run this in phpMyAdmin or MySQL CLI: mysql -u root -p < admin_setup.sql
-- ═══════════════════════════════════════════════════════════════════════════

-- 1. Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS book_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE book_db;

-- 2. Create / update book_form table
--    (If it already exists from book_form.php, we add the id column safely)
CREATE TABLE IF NOT EXISTS book_form (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(255) NOT NULL DEFAULT '',
    email    VARCHAR(255) NOT NULL DEFAULT '',
    phone    VARCHAR(50)  NOT NULL DEFAULT '',
    address  VARCHAR(500) NOT NULL DEFAULT '',
    location VARCHAR(255) NOT NULL DEFAULT '',
    guests   VARCHAR(50)  NOT NULL DEFAULT '',
    arrivals DATE,
    leaving  DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add id column if table existed without it
ALTER TABLE book_form MODIFY id INT AUTO_INCREMENT PRIMARY KEY;

-- Add phone column if it doesn't exist (old tables may have 'number' instead)
-- (Comment this out if your table already has 'phone')
-- ALTER TABLE book_form ADD COLUMN phone VARCHAR(50) NOT NULL DEFAULT '' AFTER email;

-- 3. packages table (created automatically by packages.php, but here for reference)
CREATE TABLE IF NOT EXISTS packages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)   NOT NULL,
    description TEXT,
    price       DECIMAL(10,2)  DEFAULT 0.00,
    duration    VARCHAR(100),
    destination VARCHAR(255),
    image       VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
