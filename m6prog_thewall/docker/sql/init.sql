CREATE DATABASE IF NOT EXISTS thewall_db;

USE thewall_db;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    INDEX idx_created_at (created_at),
    INDEX idx_author (author)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_messages INT DEFAULT 0,
    total_authors INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO messages (author, content, email) VALUES
('Welkom', '👋 Welkom op The Wall! Dit is de plek waar je je gedachten kunt delen met anderen. Veel plezier!', 'welcome@thewall.local');

INSERT INTO messages (author, content, email) VALUES
('Alex de Maker', '💡 Hallo iedereen! Ik ben Alex, de maker van The Wall. Dit project is gemaakt als onderdeel van M6PROG. Veel succes met jullie berichten! 🚀', 'maker@thewall.local');
