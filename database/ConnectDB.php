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