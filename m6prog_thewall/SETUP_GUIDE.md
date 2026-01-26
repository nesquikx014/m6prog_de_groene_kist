# 🖼️ The Wall - Installation & Setup Guide

## 📋 Inhoudsopgave
1. [Quick Start](#quick-start)
2. [Prerequisites](#prerequisites)
3. [Installation Steps](#installation-steps)
4. [Testing](#testing)
5. [Troubleshooting](#troubleshooting)

---

## 🚀 Quick Start

Wil je snel beginnen? Voer deze commando's uit:

```bash
cd m6prog_thewall
bash install.sh
```

En klaar! Je site is actief op `http://localhost:8081`

---

## ⚙️ Prerequisites

Zorg dat je de volgende software hebt geïnstalleerd:

- **Docker** v20.10+ 
  ```bash
  docker --version
  ```
  
- **Docker Compose** v1.29+
  ```bash
  docker-compose --version
  ```

- **Git** (voor versie controle)
  ```bash
  git --version
  ```

### Installatie op Linux (Ubuntu/Debian)

```bash
# Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verifieer installatie
docker --version
docker-compose --version
```

---

## 📦 Installation Steps

### Stap 1: Project klonen/openen

```bash
cd /path/to/m6prog_thewall
ls -la  # Controleer of alle bestanden er zijn
```

### Stap 2: Scripts executable maken

```bash
chmod +x install.sh setup.sh reseed.sh test.sh
```

### Stap 3: Automatische installatie

**Optie A: Alles in één stap**
```bash
bash install.sh
```

**Optie B: Stap voor stap**

1. Setup Docker containers:
```bash
bash setup.sh
```

2. Seed test data:
```bash
bash reseed.sh
```

3. Run tests:
```bash
bash test.sh
```

### Stap 4: Controleren

```bash
# Controleer containers
docker-compose ps

# Controleer logs
docker-compose logs -f web

# Test database
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT * FROM messages;"
```

---

## 🧪 Testing

### Test 1: Database Verbinding

```bash
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT 'Connected!' as status;"
```

**Verwacht output:**
```
| status      |
|-------------|
| Connected!  |
```

### Test 2: Web Server

```bash
# PHP version controleren
docker-compose exec php php -v

# PHP files testen
docker-compose exec php php /var/www/html/index.php
```

### Test 3: Test Data

```bash
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT author, LEFT(content, 50) FROM messages ORDER BY created_at DESC;"
```

**Verwacht output:**
```
| author           | LEFT(content, 50)                                  |
|------------------|----------------------------------------------------|
| Alex de Maker    | 💡 Hallo iedereen! Ik ben Alex, de maker van...   |
| Welkom           | 👋 Welkom op The Wall! Dit is de plek waar...     |
```

### Test 4: Website in Browser

Open je browser en ga naar:
- **Site:** http://localhost:8081
- **Verwacht:** The Wall message board met 2 test berichten

---

## 🐛 Troubleshooting

### Probleem: "Docker not found"

```bash
# Controleer installatie
docker --version

# Als niet geïnstalleerd, installeer Docker:
# Zie Prerequisites sectie
```

### Probleem: "Port 8081 already in use"

```bash
# Controleer welk process port 8081 gebruikt
lsof -i :8081

# Of wijzig port in docker-compose.yml
# Verander "8081:80" naar "8082:80"
```

### Probleem: Database connection failed

```bash
# Wacht wat langer en probeer opnieuw
sleep 15
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT 1;"

# Check database logs
docker-compose logs mariadb
```

### Probleem: Container crashes

```bash
# Check logs
docker-compose logs

# Restart containers
docker-compose down
docker-compose up -d

# Wacht 15 seconden
sleep 15
docker-compose ps
```

### Probleem: Permission denied op scripts

```bash
# Make scripts executable
chmod +x *.sh

# Of gebruik bash explicitly
bash setup.sh
```

---

## 📊 Directstructuur

```
m6prog_thewall/
├── public/
│   ├── index.php           # Main application
│   └── css/
│       └── style.css       # Styling
├── source/
│   ├── config.php          # Configuration
│   ├── database.php        # Database class
│   └── models/
│       └── Message.php     # Message model
├── docker/
│   ├── nginx/
│   │   └── nginx.conf      # Web server config
│   └── sql/
│       └── init.sql        # Database init
├── docker-compose.yml      # Docker setup
├── DATABASE_DESIGN.md      # Database schema
├── install.sh              # Automated installer
├── setup.sh                # Setup script
├── reseed.sh               # Database reseed
├── test.sh                 # Test suite
└── README.md               # This file
```

---

## 🔒 Security

Deze setup bevat voorbeelden van beveiligde praktijken:

✅ **SQL Injection Prevention**
- Prepared statements met PDO
- Parameterized queries

✅ **XSS Prevention**
- Input escaping met `htmlspecialchars()`
- Output filtering

✅ **Data Validation**
- Server-side validation
- Input sanitization

✅ **Soft Delete**
- Berichten worden gearchiveerd, niet verwijderd
- Behoudt historische data

---

## 📚 Database Commands

### Berichten ophalen
```sql
-- Alle berichten (nieuwste eerst)
SELECT * FROM messages 
WHERE is_deleted = FALSE 
ORDER BY created_at DESC 
LIMIT 50;

-- Berichten van specifieke auteur
SELECT * FROM messages 
WHERE author = 'Welkom' 
AND is_deleted = FALSE;

-- Statistieken
SELECT COUNT(*) as total_messages 
FROM messages 
WHERE is_deleted = FALSE;
```

### Berichten beheren
```sql
-- Bericht verwijderen (soft delete)
UPDATE messages 
SET is_deleted = TRUE 
WHERE id = 1;

-- Bericht herstellen
UPDATE messages 
SET is_deleted = FALSE 
WHERE id = 1;

-- Bericht hardmatig verwijderen
DELETE FROM messages WHERE id = 1;
```

---

## 🚦 Docker Commands

### Containers beheren
```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# Check status
docker-compose ps

# View logs
docker-compose logs -f web
```

### Database commands
```bash
# Connect to database
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db

# Execute query
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT * FROM messages;"

# Backup database
docker-compose exec mariadb mysqldump -u thewall_user -pthewall_secure_pass thewall_db > backup.sql

# Restore database
docker-compose exec -T mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db < backup.sql
```

---

## 📞 Support

Problemen? Check:
1. [Troubleshooting](#troubleshooting) sectie
2. Docker logs: `docker-compose logs`
3. Database status: `docker-compose ps`
4. PHP errors: Check browser console en server logs

---

## ✨ Volgende Stappen

Nu je The Wall werkend hebt, kun je:

1. **Customize styling** - Bewerk `public/css/style.css`
2. **Add features** - Voeg likes, comments toe via database tabellen
3. **Deployment** - Deploy naar production server
4. **Monitoring** - Setup monitoring en logging

---

**Gemaakt voor M6PROG - January 2026**
