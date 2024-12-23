<?php
class Database {
    private $host = "localhost";
    private $db_name = "btl_web";
    private $username = "root";
    private $port = "3307"; // Cổng MySQL của bạn
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Đảm bảo cổng và các thông số khác là chính xác
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            // echo "Kết nối thành công!";
        } catch (PDOException $exception) {
            echo "Kết nối thất bại: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
