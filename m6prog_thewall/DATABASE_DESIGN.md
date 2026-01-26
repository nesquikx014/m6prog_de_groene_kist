# Database Design - The Wall Message Board

## 📊 Schema Design

### Overzicht
Dit is een eenvoudig maar uitbreidbaar ontwerp voor een message board ("The Wall") waar gebruikers anoniem berichten kunnen plaatsen.

---

## 📋 Tabellen

### 1. **messages** - Berichten/Posts
```sql
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted BOOLEAN DEFAULT FALSE,
    INDEX idx_created_at (created_at),
    INDEX idx_author (author)
);
```

**Velden:**
- `id` - Unieke bericht ID
- `author` - Naam van de auteur (verplicht)
- `content` - Inhoud van het bericht (verplicht)
- `email` - Optioneel e-mailadres auteur
- `created_at` - Aanmaakdatum (automatisch)
- `updated_at` - Wijzigingsdatum (automatisch)
- `is_deleted` - Soft delete flag (voor archivering)

**Indices:**
- `idx_created_at` - Voor snelle sortering op datum
- `idx_author` - Voor filteren op auteur

---

### 2. **statistics** - Statistieken (Optioneel)
```sql
CREATE TABLE statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_messages INT DEFAULT 0,
    total_authors INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Doel:** Snelle statistieken zonder COUNT queries

---

## 🔐 Veiligheidsconsideraties

1. **HTML/Script Injection Prevention**
   - Alle user input wordt ge-escaped in PHP
   - Gebruik `htmlspecialchars()` bij output

2. **SQL Injection Prevention**
   - PDO prepared statements gebruiken
   - Parameterized queries

3. **XSS Prevention**
   - Input validation op client en server
   - Output escaping

4. **Soft Delete**
   - Berichten krijgen `is_deleted = TRUE` in plaats van verwijderd te worden
   - Behoud van historische data

---

## 📈 Mogelijke Uitbreidingen

```sql
-- Toekomstige tabel voor likes/reactions
CREATE TABLE reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    reaction_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);

-- Toekomstige tabel voor commentaren
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    author VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);

-- Toekomstige tabel voor moderation/reports
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);
```

---

## 🎯 Design Rationale

✅ **Simpel** - Makkelijk te begrijpen en implementeren
✅ **Schaalbaar** - Kan uitgebreid worden met extra features
✅ **Performant** - Indices op veelgebruikte velden
✅ **Veilig** - Prepared statements en input validation
✅ **Flexibel** - Soft delete voor data behoud

---

## 📊 Voorbeeld Queries

```sql
-- Alle berichten (nieuwste eerst)
SELECT * FROM messages 
WHERE is_deleted = FALSE 
ORDER BY created_at DESC 
LIMIT 50;

-- Berichten van specifieke auteur
SELECT * FROM messages 
WHERE author = 'Jan de Vries' 
AND is_deleted = FALSE
ORDER BY created_at DESC;

-- Statistieken
SELECT COUNT(*) as total_messages, 
       COUNT(DISTINCT author) as unique_authors,
       MAX(created_at) as latest_message
FROM messages 
WHERE is_deleted = FALSE;
```

---

## 🗂️ Normalisatie

✅ **1NF** - Atomic values (geen arrays/objects in kolommen)
✅ **2NF** - Alle non-key velden afhankelijk van primary key
✅ **3NF** - Geen transitieve afhankelijkheden

Toelichting: Dit is een simpel design met slechts 1 main tabel, dus normalisering is niet complex.
