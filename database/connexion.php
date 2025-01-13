<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'youdemy'; // Vérifiez ce nom
    private $username = 'root'; // Modifiez si nécessaire
    private $password = ''; // Modifiez si nécessaire
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
