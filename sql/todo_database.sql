-- SAO Quest Manager Database
-- Import ini di phpMyAdmin

CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE todo_app;

-- ── USER STATS ──
CREATE TABLE IF NOT EXISTS user_stats (
    id       INT PRIMARY KEY DEFAULT 1,
    level    INT DEFAULT 1,
    current_xp INT DEFAULT 0,
    max_xp   INT DEFAULT 100,
    hp       INT DEFAULT 100,
    max_hp   INT DEFAULT 100,
    gold     INT DEFAULT 0
);
INSERT IGNORE INTO user_stats VALUES (1, 1, 0, 100, 100, 100, 0);

-- ── HABITS ──
CREATE TABLE IF NOT EXISTS habits (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    task       VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO habits (task) VALUES
('Olahraga pagi'),
('Baca buku 20 menit'),
('Minum air 8 gelas');

-- ── DAILIES ──
CREATE TABLE IF NOT EXISTS dailies (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    task                VARCHAR(255) NOT NULL,
    is_completed        TINYINT(1) DEFAULT 0,
    last_completed_date DATETIME DEFAULT NULL,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO dailies (task) VALUES
('Review catatan kuliah'),
('Push ke GitHub'),
('Cek email & tugas');

-- ── TODOS ──
CREATE TABLE IF NOT EXISTS todos (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    task       VARCHAR(255) NOT NULL,
    completed  TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
INSERT IGNORE INTO todos (task) VALUES
('Selesaikan project portfolio'),
('Belajar React dasar'),
('Setup GitHub Pages');

-- ── REWARDS / SHOP ──
CREATE TABLE IF NOT EXISTS rewards (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    cost      INT NOT NULL DEFAULT 10
);
INSERT IGNORE INTO rewards (item_name, cost) VALUES
('Nonton anime 1 episode', 20),
('Jajan favorit', 30),
('Main game 1 jam', 50),
('Hari bebas tugas', 100);
