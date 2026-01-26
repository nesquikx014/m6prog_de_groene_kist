#!/bin/bash

# The Wall - Reset and Reseed Database
# Dit script reset de database en voegt test data toe

echo "================================================"
echo "🔄 Resetting Database and Seeding Test Data"
echo "================================================"
echo ""

# Kleuren
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Connecting to database...${NC}"

# Execute SQL to reset and seed
docker-compose exec -T mariadb mysql -u thewall_user -p"thewall_secure_pass" thewall_db << 'EOF'

-- Clear existing data (but keep structure)
TRUNCATE TABLE messages;

-- Insert test data
INSERT INTO messages (author, content, email) VALUES
('Welkom', '👋 Welkom op The Wall! Dit is de plek waar je je gedachten kunt delen met anderen. Veel plezier!', 'welcome@thewall.local'),
('Alex de Maker', '💡 Hallo iedereen! Ik ben Alex, de maker van The Wall. Dit project is gemaakt als onderdeel van M6PROG. Veel succes met jullie berichten! 🚀', 'maker@thewall.local');

-- Verify the data
SELECT 'Test data inserted successfully!' as status;
SELECT COUNT(*) as total_messages FROM messages;
SELECT author, LEFT(content, 50) as preview FROM messages ORDER BY created_at DESC;

EOF

echo ""
echo -e "${GREEN}✅ Database reset and seeded successfully!${NC}"
echo ""
