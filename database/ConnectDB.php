<?php
class ConnectDB
{
    private static ?ConnectDB $instance = null;
    private \mysqli $conn;

    private function __construct()
    {
        $servername = 'localhost';
        $username   = 'root';
        $password   = '';
        $dbname     = 'db_quanlythuvien';

        $this->conn = new \mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die('Kết nối thất bại: ' . $this->conn->connect_error);
        }

        // Thiết lập múi giờ chuẩn Việt Nam cho ứng dụng (cả PHP và MySQL)
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->conn->query("SET time_zone = '+07:00'");
        $this->conn->set_charset('utf8mb4');
    }

    public static function getInstance(): ConnectDB
    {
        if (self::$instance === null) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }

    public function getConnection(): \mysqli
    {
        return $this->conn;
    }

    public function __destruct()
    {
        if (isset($this->conn)) {
            $this->conn->close();
        }
    }
}
?>