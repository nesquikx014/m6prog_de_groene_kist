# DigiPost ERD

Entities:

- users
  - id (PK)
  - email (unique)
  - display_name
  - token (unique, generated UUID)
  - created_at

- messages
  - id (PK)
  - sender_id (FK -> users.id)
  - recipient_id (FK -> users.id)
  - subject
  - body (LONGTEXT)
  - is_read
  - created_at

Relationships:
- users 1..n -> messages (as sender)
- users 1..n -> messages (as recipient)

Notes:
- Tokens are unique strings (UUID) and are generated automatically by a trigger when inserting a user.
- Message bodies use LONGTEXT to allow very long messages.

SQL migration scripts are in `docker/sql-digipost/` and will be executed automatically by the MariaDB container at startup.
