<?php
// Database Connection Class

class Database {
    private $connection;
    private $host;
    private $db_name;
    private $db_user;
    private $db_pass;

    public function __construct() {
        $this->host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASS;
        
        $this->connect();
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->db_user,
                $this->db_pass
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Database Connection Error: ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        return $this->connection->prepare($sql);
    }

    public function test() {
        try {
            $stmt = $this->connection->prepare("SELECT 1");
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
