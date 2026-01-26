<?php
// Message Model with dataclass-like structure
                                                 
require_once __DIR__ . '/../database.php';

/**
 * MessageData - Dataclass for message data
 */
class MessageData {
    public int $id;
    public string $author;
    public string $content;
    public ?string $email;
    public string $created_at;
    public string $updated_at;
    public bool $is_deleted;

    public function __construct(
        int $id = 0,
        string $author = '',
        string $content = '',
        ?string $email = null,
        string $created_at = '',
        string $updated_at = '',
        bool $is_deleted = false
    ) {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->is_deleted = $is_deleted;
    }

    /**
     * Create MessageData instance from database array
     */
    public static function fromArray(array $data): self {
        return new self(
            id: (int)$data['id'],
            author: $data['author'],
            content: $data['content'],
            email: $data['email'] ?? null,
            created_at: $data['created_at'],
            updated_at: $data['updated_at'] ?? $data['created_at'],
            is_deleted: (bool)($data['is_deleted'] ?? false)
        );
    }
}

/**
 * Message - Repository class for message operations
 */
class Message {
    private $db;
    private $table = 'messages';

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all messages as MessageData objects
     * @return MessageData[]
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_deleted = FALSE ORDER BY created_at DESC");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([MessageData::class, 'fromArray'], $results);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get message by ID as MessageData object
     * @return MessageData|null
     */
    public function getById($id) {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE id = :id AND is_deleted = FALSE");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? MessageData::fromArray($result) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Create new message
     */
    public function create($author, $content) {
        try {
            $stmt = $this->db->query(
                "INSERT INTO {$this->table} (author, content) VALUES (:author, :content)"
            );
            return $stmt->execute([
                ':author' => htmlspecialchars($author),
                ':content' => htmlspecialchars($content)
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete message
     */
    public function delete($id) {
        try {
            $stmt = $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
