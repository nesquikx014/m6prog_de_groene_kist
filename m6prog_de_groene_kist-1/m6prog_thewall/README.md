# The Wall - Message Board Project

A simple message board application built with PHP, MySQL, and Docker.

## Project Structure

```
m6prog_thewall/
├── docker/
│   ├── nginx/
│   │   └── nginx.conf          # Nginx web server configuration
│   └── sql/
│       └── 01_init.sql         # Database initialization script
├── public/
│   ├── css/
│   │   └── style.css           # Styling for the application
│   └── index.php               # Main application entry point
├── source/
│   ├── config.php              # Configuration file (loads from .env)
│   └── database.php            # Database connection and functions
├── docker-compose.yml          # Docker services definition
├── .env                        # Environment variables
└── README.md                   # This file
```

## Features

- ✨ Modern, responsive design with gradient styling
- 🗄️ MySQL database for storing messages
- 🐘 PHP backend for message management
- 📱 Fully responsive layout
- 🔐 Security measures (input sanitization)
- 📊 Database status indicator

## Quick Start

### Prerequisites
- Docker
- Docker Compose

### Installation

1. Navigate to the project directory:
```bash
cd /home/slimbook/SD2C/PROG/m6prog_thewall
```

2. Start the Docker containers:
```bash
docker-compose up -d
```

3. Access the application:
- **Main application**: http://localhost:89
- **phpMyAdmin**: http://localhost:1089
  - User: `thewall_user`
  - Password: `TheWall123!`

### Stopping the Application

```bash
docker-compose down
```

## Environment Configuration

The `.env` file contains:
- `DB_HOST`: Database host (mariadb)
- `DB_USER`: Database user (thewall_user)
- `DB_PASSWORD`: Database password (TheWall123!)
- `DB_SCHEMA_NAME`: Database name (thewall_db)

## Database

The database includes a `messages` table with:
- `id`: Auto-incrementing primary key
- `author`: Message author name
- `content`: Message content
- `created_at`: Timestamp of creation

## Technologies Used

- **PHP 7.4+**: Server-side scripting
- **MySQL/MariaDB**: Database management
- **Nginx**: Web server
- **Docker**: Containerization
- **CSS3**: Styling with gradients and animations

## Development Notes

- All user input is sanitized with `htmlspecialchars()` to prevent XSS attacks
- Database connection errors are properly handled
- Responsive design works on all screen sizes
- Modern CSS features for better UX

## Future Enhancements

- User authentication
- Message editing and deletion
- Like/reaction system
- Comment functionality
- Search and filtering
