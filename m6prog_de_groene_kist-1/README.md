# m6prog_de_groene_kist

## DigiPost Docker setup (m6prog_digipost)

This project contains a separate Docker setup for the DigiPost exercise. Files related to the DigiPost setup:

- `docker-compose.digipost.yml` - Compose file for the DigiPost stack
- `docker/nginx/digipost.conf` - nginx config used by the DigiPost stack
- `docker/env/.env.digipost` - environment variables for the DigiPost stack (DB credentials + API token)
- `docker/sql-digipost/01_init.sql` - DB init script: creates `users` and `messages` tables and inserts initial sample data
- `docker/sql-digipost/02_triggers.sql` - DB trigger that ensures `users.token` is set to a unique UUID on insert


## Database design & tokens

- ERD is saved in `docs/ERD.md` (users + messages with relationships)
- Message bodies use `LONGTEXT` to allow long messages
- `users.token` is a unique UUID for authorization; it is generated automatically via a trigger (see `02_triggers.sql`) and the column is UNIQUE
- The DB scripts are executed automatically by the MariaDB Docker image on container start (see `docker/sql-digipost/`)



Quick start (from project root):

1. Start DigiPost stack: `docker compose -f docker-compose.digipost.yml up -d`
2. Visit the site at: `http://localhost:8080` (or `http://<your-docker-host>:8080`)
3. DB test page: `http://localhost:8080/?dbtest=1` — verifies DB connection and lists tables ✅
4. API: `http://localhost:8080/api.php` (use header `Authorization: Bearer secret_token_123` or `?api_token=secret_token_123` for dev)

Notes:
- DB user: `digipost_user` / `DigipostPass123` — database: `m6prog_digipost`
- phpMyAdmin: http://localhost:1089 (user `digipost_user`)

Testing the API (examples):

- List messages:
  - curl -H "Authorization: Bearer secret_token_123" "http://localhost:8080/api.php?op=list"
- Read message:
  - curl -H "Authorization: Bearer secret_token_123" "http://localhost:8080/api.php?op=read&id=1"
- Create message (JSON):
  - curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer secret_token_123" -d '{"sender_id":1,"recipient_id":2,"subject":"Test","body":"Hello"}' "http://localhost:8080/api.php"
- Mark as read (PUT):
  - curl -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer secret_token_123" -d '{"id":1,"is_read":1}' "http://localhost:8080/api.php"

To inspect user tokens (for testing purposes) run in SQL:

  SELECT id, email, token FROM users;

Please show this to your teacher so they can verify the design, setup and sign off.
