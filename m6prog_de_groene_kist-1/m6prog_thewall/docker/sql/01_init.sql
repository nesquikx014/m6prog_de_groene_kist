-- Create messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO messages (author, content) VALUES 
('Alice', 'This is the first message on the wall!'),
('Bob', 'Welcome to The Wall - a simple message board'),
('Charlie', 'You can post your thoughts here.');
