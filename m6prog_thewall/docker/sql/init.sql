-- The Wall Database Initialization
-- Automated setup script for message board application

CREATE DATABASE IF NOT EXISTS thewall_db;

USE thewall_db;

-- =====================================================
-- Main table: messages
-- =====================================================
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(100) NOT NULL COMMENT 'Naam van de auteur',
    content TEXT NOT NULL COMMENT 'Inhoud van het bericht',
    email VARCHAR(100) COMMENT 'E-mail van auteur (optioneel)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Aanmaakdatum',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Wijzigingsdatum',
    is_deleted BOOLEAN DEFAULT FALSE COMMENT 'Soft delete flag',
    INDEX idx_created_at (created_at),
    INDEX idx_author (author)
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Optional: statistics table
-- =====================================================
CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_messages INT DEFAULT 0,
    total_authors INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert test data
-- =====================================================

-- Welcome message
INSERT INTO messages (author, content, email) VALUES
('Welkom', '👋 Welkom op The Wall! Dit is de plek waar je je gedachten kunt delen met anderen. Veel plezier!', 'welcome@thewall.local');

-- Maker message
INSERT INTO messages (author, content, email) VALUES
('Alex de Maker', '💡 Hallo iedereen! Ik ben Alex, de maker van The Wall. Dit project is gemaakt als onderdeel van M6PROG. Veel succes met jullie berichten! 🚀', 'maker@thewall.local');
