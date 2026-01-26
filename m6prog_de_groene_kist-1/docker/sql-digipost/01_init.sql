-- Users table: stores recipients/authors with unique tokens
USE m6prog_digipost;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  display_name VARCHAR(100) DEFAULT NULL,
  token VARCHAR(36) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (email, display_name, token) VALUES
('alice@example.com','Alice', UUID()),
('bob@example.com','Bob', UUID());

-- Messages table: text can be long so use LONGTEXT, and messages reference users for sender and recipient
CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT NOT NULL,
  recipient_id INT NOT NULL,
  subject VARCHAR(255) NOT NULL,
  body LONGTEXT NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO messages (sender_id, recipient_id, subject, body) VALUES
(1,2,'Welkom','Welkom in je DigiPost!'),
(2,1,'Afspraak','Zullen we volgende week afspreken?');
