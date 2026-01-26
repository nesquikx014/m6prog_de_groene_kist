# 🧪 The Wall - Testing & Verification Guide

## 📋 Test Checklist

### ✅ Infrastructure Tests

- [ ] Docker is installed (`docker --version`)
- [ ] Docker Compose is installed (`docker-compose --version`)
- [ ] All ports are available (8081, 3308)
- [ ] Sufficient disk space for containers (5GB minimum)

### ✅ Setup Tests

- [ ] `docker-compose.yml` is valid
- [ ] All required files exist
- [ ] Scripts are executable (`chmod +x *.sh`)
- [ ] No syntax errors in PHP files

### ✅ Database Tests

- [ ] Database container starts successfully
- [ ] Database user can authenticate
- [ ] Messages table exists
- [ ] Test data is inserted correctly
- [ ] Indexes are created

### ✅ Web Server Tests

- [ ] Nginx container starts
- [ ] PHP-FPM container starts
- [ ] Web server listens on port 8081
- [ ] Static files (CSS) are accessible

### ✅ Application Tests

- [ ] PHP application loads without errors
- [ ] Database connection successful
- [ ] Messages display correctly
- [ ] Form submission works
- [ ] Test data displays on page

---

## 🚀 Testing Procedures

### Test 1: Docker Setup

```bash
# Check Docker status
docker ps
docker-compose ps

# Expected output:
# CONTAINER ID   IMAGE           STATUS      PORTS
# xxx            nginx:latest    Up 2 min    0.0.0.0:8081->80/tcp
# yyy            php:8.2-fpm     Up 2 min
# zzz            mariadb:latest  Up 2 min    0.0.0.0:3308->3306/tcp
```

### Test 2: Database Connection

```bash
# Test database access
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT VERSION() as version;"

# Expected output:
# | version            |
# |------------------|
# | 10.5.13-MariaDB  |
```

### Test 3: Database Tables

```bash
# Check messages table structure
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "DESC messages;"

# Expected output:
# Field        | Type         | Null | Key | Default | Extra
# id           | int(11)      | NO   | PRI | NULL    | auto_increment
# author       | varchar(100) | NO   |     | NULL    |
# content      | text         | NO   |     | NULL    |
# email        | varchar(100) | YES  |     | NULL    |
# created_at   | timestamp    | NO   |     | CURRENT_TIMESTAMP |
# ...
```

### Test 4: Test Data

```bash
# Check if test data exists
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT COUNT(*) as message_count FROM messages WHERE is_deleted = FALSE;"

# Expected output:
# | message_count |
# |--------------|
# | 2            |

# View test data
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT author, LEFT(content, 60) as preview FROM messages WHERE is_deleted = FALSE ORDER BY created_at DESC;"

# Expected output:
# | author           | preview                                                |
# |------------------|--------------------------------------------------------|
# | Alex de Maker    | 💡 Hallo iedereen! Ik ben Alex, de maker van...      |
# | Welkom           | 👋 Welkom op The Wall! Dit is de plek waar je...    |
```

### Test 5: Web Server

```bash
# Test PHP
docker-compose exec php php -v
# Expected: PHP 8.2.x

# Test configuration files
docker-compose exec php test -f /var/www/html/index.php && echo "✅ index.php exists"
docker-compose exec php test -f /var/www/html/css/style.css && echo "✅ style.css exists"

# Check PHP errors
docker-compose exec php php -l /var/www/html/index.php
# Expected: No syntax errors
```

### Test 6: Website Access

**Via Browser:**

1. Open http://localhost:8081
2. Check if page loads without errors
3. Check for:
   - ✅ Header "🖼️ The Wall"
   - ✅ Form for new messages
   - ✅ 2 test messages displayed
   - ✅ Database connection indicator (✅ or ❌)
   - ✅ Proper styling (CSS loaded)
   - ✅ Responsive design (test on mobile)

**Via Command Line:**

```bash
# Test if page is accessible
curl http://localhost:8081

# Expected: HTML content returned (no errors)

# Test form submission via curl
curl -X POST http://localhost:8081 \
  -d "author=Test User&content=This is a test message"

# Expected: Page reloads with new message displayed
```

### Test 7: Form Submission

**Manual Test:**

1. Navigate to http://localhost:8081
2. Fill in the form:
   - Name: "Test User"
   - Message: "This is a test message"
3. Click "Plaatsen"
4. Expected result:
   - ✅ Form resets
   - ✅ Success message appears
   - ✅ New message appears in the wall
   - ✅ Timestamp shows current time

**Automated Test:**

```bash
# Submit form via API
curl -X POST http://localhost:8081 \
  -d "author=API+Test&content=Message+from+API"

# Check if message was inserted
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT author, content FROM messages WHERE author = 'API Test';"
```

### Test 8: Data Integrity

```bash
# Check for orphaned data
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT COUNT(*) FROM messages;"

# Check for deleted messages
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT COUNT(*) FROM messages WHERE is_deleted = TRUE;"

# Check timestamps
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT author, created_at, updated_at FROM messages ORDER BY created_at DESC LIMIT 3;"
```

### Test 9: Error Handling

**Test Empty Form:**
1. Try to submit empty message
2. Expected: Error message appears

**Test XSS Protection:**
```bash
# Try to inject script
curl -X POST http://localhost:8081 \
  -d "author=Test&content=<script>alert('XSS')</script>"

# Check if script is escaped in database
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT content FROM messages WHERE author = 'Test';"

# Expected: Script tags should be visible as text, not executed
```

**Test SQL Injection:**
```bash
# Try SQL injection
curl -X POST http://localhost:8081 \
  -d "author=Admin'; DROP TABLE messages; --&content=Test"

# Expected: Table still exists
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "DESC messages;"
```

### Test 10: Performance

```bash
# Load test (add 100 messages quickly)
for i in {1..100}; do
  curl -X POST http://localhost:8081 \
    -d "author=LoadTest$i&content=Message $i"
  echo "Inserted message $i"
done

# Check total messages
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT COUNT(*) as total FROM messages;"

# Check page load time
time curl http://localhost:8081 > /dev/null

# Expected: < 500ms for page load
```

---

## 📊 Test Results Template

```
═══════════════════════════════════════════════════
THE WALL - TEST RESULTS
═══════════════════════════════════════════════════

Date: 2026-01-26
Tester: [Your Name]
Environment: Docker / Linux

INFRASTRUCTURE
──────────────
Docker Installed:          ✅ PASS / ❌ FAIL
Docker Compose:            ✅ PASS / ❌ FAIL
Ports Available:           ✅ PASS / ❌ FAIL

DATABASE
────────
Container Starts:          ✅ PASS / ❌ FAIL
User Authentication:       ✅ PASS / ❌ FAIL
Messages Table:            ✅ PASS / ❌ FAIL
Test Data:                 ✅ PASS / ❌ FAIL
Indexes:                   ✅ PASS / ❌ FAIL

WEB SERVER
──────────
Nginx Starts:              ✅ PASS / ❌ FAIL
PHP Starts:                ✅ PASS / ❌ FAIL
Port 8081:                 ✅ PASS / ❌ FAIL
Static Files:              ✅ PASS / ❌ FAIL

APPLICATION
───────────
Page Loads:                ✅ PASS / ❌ FAIL
DB Connection:             ✅ PASS / ❌ FAIL
Messages Display:          ✅ PASS / ❌ FAIL
Form Submission:           ✅ PASS / ❌ FAIL
Error Handling:            ✅ PASS / ❌ FAIL

SECURITY
────────
XSS Protection:            ✅ PASS / ❌ FAIL
SQL Injection:             ✅ PASS / ❌ FAIL
Input Validation:          ✅ PASS / ❌ FAIL

═══════════════════════════════════════════════════
OVERALL RESULT:            ✅ ALL TESTS PASSED
═══════════════════════════════════════════════════

Notes: [Your notes here]
Issues Found: [If any]
Recommendations: [If any]
```

---

## 🔧 Debugging Tips

### If page won't load
```bash
# Check web server logs
docker-compose logs web

# Check PHP errors
docker-compose logs php

# Test PHP syntax
docker-compose exec php php -l /var/www/html/index.php
```

### If database won't connect
```bash
# Check database logs
docker-compose logs mariadb

# Test connection manually
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT 1;"

# Check environment variables
docker-compose config | grep -A5 "environment"
```

### If styles aren't loading
```bash
# Check if CSS file exists
docker-compose exec php test -f /var/www/html/css/style.css

# Check nginx configuration
docker-compose exec web cat /etc/nginx/nginx.conf

# Test curl for CSS
curl http://localhost:8081/css/style.css | head -20
```

### Clear everything and restart
```bash
# Full reset
docker-compose down -v
docker system prune -a

# Fresh start
docker-compose up -d

# Wait and test
sleep 15
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT COUNT(*) FROM messages;"
```

---

**Testing Version: 1.0**
**Last Updated: 2026-01-26**
