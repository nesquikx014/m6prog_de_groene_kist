# The Wall - Database Schema Diagram

## 📊 Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────────────┐
│             messages                    │
├─────────────────────────────────────────┤
│ PK  id                     INT          │
│ ──────────────────────────────────────  │
│     author                 VARCHAR(100) │  NOT NULL
│     content                TEXT         │  NOT NULL
│     email                  VARCHAR(100) │  NULLABLE
│     created_at             TIMESTAMP    │  DEFAULT NOW()
│     updated_at             TIMESTAMP    │  ON UPDATE NOW()
│     is_deleted             BOOLEAN      │  DEFAULT FALSE
│ ──────────────────────────────────────  │
│ INDEX idx_created_at (created_at)       │
│ INDEX idx_author (author)                │
│ ──────────────────────────────────────  │
│ ENGINE: InnoDB                          │
│ CHARSET: utf8mb4                        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│          statistics                     │
├─────────────────────────────────────────┤
│ PK  id                     INT          │
│ ──────────────────────────────────────  │
│     total_messages         INT          │  DEFAULT 0
│     total_authors          INT          │  DEFAULT 0
│     last_updated           TIMESTAMP    │  DEFAULT NOW()
│ ──────────────────────────────────────  │
│ ENGINE: InnoDB                          │
│ CHARSET: utf8mb4                        │
└─────────────────────────────────────────┘
```

## 🔄 Data Flow

```
User Input (HTML Form)
         ↓
PHP Validation ($_POST)
         ↓
htmlspecialchars() - XSS Prevention
         ↓
PDO Prepared Statement - SQL Injection Prevention
         ↓
Database INSERT
         ↓
Query SELECT * FROM messages
         ↓
Display in HTML (escaped)
```

## 📈 Sample Data Structure

```json
{
  "message": {
    "id": 1,
    "author": "Alex de Maker",
    "content": "💡 Hallo iedereen! Dit is een test bericht.",
    "email": "maker@thewall.local",
    "created_at": "2026-01-26 12:05:30",
    "updated_at": "2026-01-26 12:05:30",
    "is_deleted": false
  }
}
```

## 🔐 Security Features

### Input Validation
```
┌──────────────┐
│  User Input  │
└──────┬───────┘
       ↓
┌──────────────────────────────┐
│ Server-side Validation       │
│ - Check if fields not empty  │
│ - Limit string length        │
│ - Validate email format      │
└──────┬───────────────────────┘
       ↓
┌──────────────────────────────┐
│ Data Escaping                │
│ - htmlspecialchars()         │
│ - PDO PreparedStatement      │
└──────┬───────────────────────┘
       ↓
┌──────────────────────────────┐
│ Database Storage             │
│ - Safe from SQL Injection    │
│ - Safe from XSS              │
└──────────────────────────────┘
```

## 📊 Query Performance

### Indexes
```sql
-- Index: idx_created_at
-- Purpose: Quick sorting by creation date
-- Used in: ORDER BY created_at DESC

-- Index: idx_author
-- Purpose: Quick filtering by author
-- Used in: WHERE author = ?
```

### Typical Query Execution

```
Query: SELECT * FROM messages ORDER BY created_at DESC LIMIT 50
Execution:
  1. Use index idx_created_at ✓
  2. Get 50 most recent records ✓
  3. Return to application ✓
Time: ~10ms (depending on data volume)
```

## 📈 Scalability Considerations

### Current Capacity
- **Small deployment:** 10,000 - 100,000 messages
- **Medium deployment:** 100,000 - 1,000,000 messages
- **Optimization needed:** > 1,000,000 messages

### When to Optimize

```
Messages: 100,000
├─ Add pagination ✓
└─ Performance: GOOD

Messages: 1,000,000
├─ Add database partitioning
├─ Archive old messages
├─ Add caching layer (Redis)
└─ Performance: NEEDS WORK

Messages: 10,000,000
├─ Consider sharding
├─ Separate read/write databases
├─ Full-text search indexes
└─ Performance: REQUIRES REDESIGN
```

## 🔄 Future Enhancements

### Option 1: Add Comments
```sql
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    author VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    INDEX idx_message_id (message_id)
);
```

### Option 2: Add Reactions/Likes
```sql
CREATE TABLE reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    emoji VARCHAR(10) NOT NULL,
    count INT DEFAULT 0,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reaction (message_id, emoji)
);
```

### Option 3: Add Categories/Tags
```sql
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE message_tags (
    message_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (message_id, tag_id),
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
```

## 🧪 Test Queries

```sql
-- Get all messages with recent first
SELECT * FROM messages 
WHERE is_deleted = FALSE 
ORDER BY created_at DESC 
LIMIT 50;

-- Get messages from specific author
SELECT author, COUNT(*) as message_count 
FROM messages 
WHERE is_deleted = FALSE 
GROUP BY author 
ORDER BY message_count DESC;

-- Get statistics
SELECT 
    COUNT(*) as total_messages,
    COUNT(DISTINCT author) as unique_authors,
    MAX(created_at) as latest_message,
    MIN(created_at) as oldest_message
FROM messages 
WHERE is_deleted = FALSE;

-- Get deleted messages (for admin)
SELECT * FROM messages 
WHERE is_deleted = TRUE 
ORDER BY updated_at DESC;
```

---

**Database Design Version: 1.0**
**Last Updated: 2026-01-26**
