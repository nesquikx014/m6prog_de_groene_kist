# 🖼️ M6PROG The Wall - Message Board

Een interactieve berichtenbord-applicatie waar gebruikers anoniem berichten kunnen plaatsen. Gebouwd met PHP, Docker, en MariaDB.

## ✨ Features

✅ Berichten plaatsen met naam  
✅ Anoniem - geen login vereist  
✅ Soft delete - berichten worden gearchiveerd  
✅ Responsive design - mobile friendly  
✅ XSS en SQL injection protection  
✅ Docker containerized  
✅ Automatische setup scripts  
✅ Uitgebreide documentatie  

## 📂 Project Structuur

```
m6prog_thewall/
├── 📄 Documentation
│   ├── DATABASE_DESIGN.md      (Schema en design rationale)
│   ├── DATABASE_SCHEMA.md      (ERD en ER diagrams)
│   ├── SETUP_GUIDE.md          (Installatie instructies)
│   ├── TESTING.md              (Test procedures)
│   └── README.md               (Dit bestand)
│
├── 🖼️ Application Code
│   ├── public/
│   │   ├── index.php           (Main application)
│   │   └── css/style.css       (Styling)
│   └── source/
│       ├── config.php          (Configuration)
│       ├── database.php        (Database class)
│       └── models/
│           └── Message.php     (Message model)
│
├── 🐳 Docker & Database
│   ├── docker-compose.yml      (Container orchestration)
│   ├── docker/
│   │   ├── nginx/nginx.conf    (Web server config)
│   │   └── sql/init.sql        (Database initialization)
│
└── 🚀 Setup & Testing Scripts
    ├── install.sh              (Complete automated install)
    ├── setup.sh                (Docker setup only)
    ├── reseed.sh               (Database reset & seed)
    └── test.sh                 (Test suite)
```

## 🚀 Quick Start

### Option 1: Fully Automated
```bash
cd m6prog_thewall
bash install.sh
```

### Option 2: Manual Steps
```bash
# 1. Make scripts executable
chmod +x *.sh

# 2. Start containers
bash setup.sh

# 3. Seed test data
bash reseed.sh

# 4. Run tests
bash test.sh
```

### Option 3: Manual Docker
```bash
docker-compose up -d
sleep 15  # Wait for database to initialize
```

## 🌐 Access

- **Website:** http://localhost:8081
- **Database:** localhost:3308
  - User: `thewall_user`
  - Password: `thewall_secure_pass`

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| [DATABASE_DESIGN.md](DATABASE_DESIGN.md) | Database schema, design rationale, and future extensions |
| [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | ERD diagrams, data flow, performance considerations |
| [SETUP_GUIDE.md](SETUP_GUIDE.md) | Complete installation, troubleshooting, and Docker commands |
| [TESTING.md](TESTING.md) | Test procedures, verification checklist, debugging tips |

## 🗄️ Database

**Main Table: messages**
```sql
id              INT (PK)
author          VARCHAR(100) - Auteur naam
content         TEXT - Bericht inhoud
email           VARCHAR(100) - Optioneel e-mail
created_at      TIMESTAMP - Aanmaakdatum
updated_at      TIMESTAMP - Wijzigingsdatum
is_deleted      BOOLEAN - Soft delete flag
```

**Indices:**
- `idx_created_at` - Snelle sortering op datum
- `idx_author` - Snelle filtering op auteur

## 🔐 Security Features

✅ **SQL Injection Prevention** - PDO prepared statements  
✅ **XSS Prevention** - htmlspecialchars() escaping  
✅ **Input Validation** - Server-side validation  
✅ **Soft Delete** - Behoudt historische data  

## 📊 Test Data

De applicatie wordt geïnstalleerd met 2 test berichten:

1. **Welkom** - Welkomsbericht
2. **Alex de Maker** - Over de maker

## 🐳 Docker Services

| Service | Image | Port | Purpose |
|---------|-------|------|---------|
| web | nginx:latest | 8081 | Web server |
| php | php:8.2-fpm | - | PHP application |
| mariadb | mariadb:latest | 3308 | Database |

## 🛠️ Docker Commands

```bash
# Start
docker-compose up -d

# Stop
docker-compose down

# View logs
docker-compose logs -f web

# Database shell
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db
```

## 🧪 Testing

```bash
# Run automated tests
bash test.sh

# Or manually verify
curl http://localhost:8081
docker-compose exec mariadb mysql -u thewall_user -pthewall_secure_pass thewall_db -e "SELECT * FROM messages;"
```

## 📈 Future Enhancements

Mogelijke uitbreidingen:
- Likes/reactions system
- Comments on messages
- Categories/tags
- User accounts
- Moderation tools
- Full-text search

Zie [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) voor implementatie voorbeelden.

## 🐛 Troubleshooting

Problemen? Check:
- [SETUP_GUIDE.md](SETUP_GUIDE.md) - Installatie problemen
- [TESTING.md](TESTING.md) - Test en debug procedures
- `docker-compose logs` - Container logs
- Database query: `SELECT * FROM messages;`

## 📞 Support

1. Controleer documentatie
2. Kijk in docker logs: `docker-compose logs`
3. Test database verbinding
4. Check PHP syntax: `docker-compose exec php php -l /var/www/html/index.php`

## 📋 Project Info

- **Version:** 1.0.0
- **Created:** January 2026
- **Framework:** Docker + PHP + MariaDB
- **License:** MIT
- **Course:** M6PROG

---

**Built with ❤️ for M6PROG Web Development**
