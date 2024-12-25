<?php
class Database {
    private $host = "localhost";
    private $db_name = "btl_web";
    private $username = "root";
    private $port = "3307"; 
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            
        } catch (PDOException $exception) {
            echo "Kết nối thất bại: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
