#!/bin/bash

# The Wall - Test and Verify Setup
# Dit script test of alles correct is ingesteld

set -e

echo "================================================"
echo "🧪 Testing The Wall Setup"
echo "================================================"
echo ""

# Kleuren
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

TESTS_PASSED=0
TESTS_FAILED=0

# Test function
test_command() {
    local test_name=$1
    local command=$2
    
    echo -n "Testing: $test_name ... "
    
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}✅ PASSED${NC}"
        ((TESTS_PASSED++))
    else
        echo -e "${RED}❌ FAILED${NC}"
        ((TESTS_FAILED++))
    fi
}

echo -e "${BLUE}Infrastructure Tests${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_command "Docker daemon running" "docker ps"
test_command "docker-compose is available" "docker-compose --version"
test_command "Containers are running" "docker-compose ps | grep -q 'Up'"

echo ""
echo -e "${BLUE}Database Tests${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_command "Database is accessible" "docker-compose exec -T mariadb mysql -u thewall_user -p'thewall_secure_pass' thewall_db -e 'SELECT 1;'"
test_command "Messages table exists" "docker-compose exec -T mariadb mysql -u thewall_user -p'thewall_secure_pass' thewall_db -e 'DESC messages;'"
test_command "Has test data" "docker-compose exec -T mariadb mysql -u thewall_user -p'thewall_secure_pass' thewall_db -e 'SELECT COUNT(*) FROM messages;' | grep -q '[1-9]'"

echo ""
echo -e "${BLUE}Web Server Tests${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_command "Web server is running" "docker-compose exec -T php php -v"
test_command "index.php exists" "docker-compose exec -T php test -f /var/www/html/index.php"
test_command "CSS file exists" "docker-compose exec -T php test -f /var/www/html/css/style.css"

echo ""
echo -e "${BLUE}Application Tests${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

test_command "Database config is accessible" "docker-compose exec -T php test -f /var/www/source/config.php"
test_command "Database class exists" "docker-compose exec -T php test -f /var/www/source/database.php"
test_command "Message model exists" "docker-compose exec -T php test -f /var/www/source/models/Message.php"

echo ""
echo "================================================"
echo "📊 Test Results"
echo "================================================"
echo -e "Passed: ${GREEN}$TESTS_PASSED${NC}"
echo -e "Failed: ${RED}$TESTS_FAILED${NC}"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed!${NC}"
    echo ""
    echo "🌐 Access your site at: http://localhost:8081"
    echo ""
    exit 0
else
    echo -e "${RED}❌ Some tests failed!${NC}"
    echo ""
    echo "Debug tips:"
    echo "  - Check docker-compose logs: docker-compose logs"
    echo "  - Verify containers: docker-compose ps"
    echo "  - Check database: docker-compose exec mariadb mysql -u root -pthewall_root_pass"
    echo ""
    exit 1
fi
