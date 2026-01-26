-- --------------------------------------------------
-- Testdata voor De Groene Kist
-- --------------------------------------------------

-- Vul categories
INSERT INTO categories (name, description) VALUES
('Groente', 'Verse groenten van lokale boeren'),
('Fruit', 'Heerlijk vers fruit');

-- Vul products
INSERT INTO products (name, category_id, price, stock) VALUES
-- Groente (5 stuks)
('Tomaat', 1, 0.75, 120),
('Komkommer', 1, 0.60, 80),
('Wortel', 1, 0.50, 100),
('Paprika', 1, 0.90, 60),
('Sla', 1, 0.40, 50),

-- Fruit (5 stuks)
('Appel', 2, 0.50, 100),
('Banaan', 2, 0.30, 150),
('Sinaasappel', 2, 0.60, 120),
('Peer', 2, 0.55, 80),
('Druif', 2, 0.70, 90);

-- Vul offers (aanbiedingen)
INSERT INTO offers (product_id, discount_percentage, start_date, end_date) VALUES
(1, 10, '2025-11-25', '2025-12-01'), -- Tomaat
(2, 15, '2025-11-25', '2025-12-03'), -- Komkommer
(6, 5, '2025-11-25', '2025-11-30');  -- Appel

-- Voeg eventueel test users toe
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$abcdefghijklmnopqrstuv', 'admin'),
('tester', '$2y$10$abcdefghijklmnopqrstuv', 'user');
