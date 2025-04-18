<?php
class Database {
    private $host = "127.0.0.1";  // Changed from localhost to 127.0.0.1
    private $db_name = "projetweb";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            $this->conn->exec("SET NAMES utf8mb4");
        } catch(PDOException $exception) {
            die("Database connection error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>