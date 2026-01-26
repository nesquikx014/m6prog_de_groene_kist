#!/bin/bash

# The Wall - Automated Setup Script
# Dit script installeert en configureert alles automatisch

set -e  # Stop bij eerste error

echo "================================================"
echo "🖼️  THE WALL - Automated Setup"
echo "================================================"
echo ""

# Kleuren
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is installed
echo -e "${YELLOW}[1/5]${NC} Checking Docker installation..."
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker is not installed!${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Docker found${NC}"

# Check if docker-compose is installed
echo -e "${YELLOW}[2/5]${NC} Checking Docker Compose installation..."
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose is not installed!${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Docker Compose found${NC}"

# Remove old containers and volumes
echo -e "${YELLOW}[3/5]${NC} Cleaning up old containers..."
docker-compose down -v 2>/dev/null || true
echo -e "${GREEN}✅ Cleanup complete${NC}"

# Start Docker containers
echo -e "${YELLOW}[4/5]${NC} Starting Docker containers..."
docker-compose up -d
echo -e "${GREEN}✅ Containers started${NC}"

# Wait for database to be ready
echo -e "${YELLOW}[5/5]${NC} Waiting for database..."
sleep 10

# Test database connection
echo ""
echo "================================================"
echo "🔍 Testing Database Connection..."
echo "================================================"

docker-compose exec -T mariadb mysql -u thewall_user -p"thewall_secure_pass" thewall_db -e "SELECT 'Database connection successful!' as status;" 2>/dev/null && \
    echo -e "${GREEN}✅ Database connected successfully!${NC}" || \
    echo -e "${RED}❌ Database connection failed!${NC}"

echo ""
echo "================================================"
echo "✅ Setup Complete!"
echo "================================================"
echo ""
echo "🌐 Access your site at:"
echo "   http://localhost:8081"
echo ""
echo "📊 Database credentials:"
echo "   Host: localhost:3308"
echo "   Database: thewall_db"
echo "   User: thewall_user"
echo "   Password: thewall_secure_pass"
echo ""
echo "🛑 To stop the containers, run:"
echo "   docker-compose down"
echo ""
