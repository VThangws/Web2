<?php
class DBConnect
{
    private static $instance = null;
    private $pdo;

    private $host = 'localhost';
    private $dbname = 'db_quanlythuvien';
    private $username = 'root';
    private $password = '';

    private function __construct()
    {
        // Load .env from project root if present
        $root = dirname(__DIR__);
        $envFile = $root . DIRECTORY_SEPARATOR . '.env';
        if (is_readable($envFile)) {
            $this->loadEnvFile($envFile);
        }

        // Allow configuration via environment variables
        $this->host = getenv('DB_HOST') ?: $this->host;
        $this->dbname = getenv('DB_NAME') ?: $this->dbname;
        $this->username = getenv('DB_USER') ?: $this->username;
        $this->password = getenv('DB_PASS') ?: $this->password;
        $port = getenv('DB_PORT') ?: '3306';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};port={$port};dbname={$this->dbname};charset={$charset}",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new RuntimeException("Kết nối thất bại: " . $e->getMessage(), 0, $e);
        }
    }

    private function loadEnvFile(string $path): void
    {
        $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) return;
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || substr($line, 0, 1) === '#') continue;
            $pos = strpos($line, '=');
            if ($pos === false) continue;
            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));
            $value = trim($value, "\"' ");
            if ($key !== '') {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DBConnect();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function selectOne($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function count($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        return $this->execute($sql, array_values($data));
    }

    public function update($table, $data, $whereClause, $whereParams = [])
    {
        $setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $sql = "UPDATE `$table` SET $setClause WHERE $whereClause";
        return $this->execute($sql, array_merge(array_values($data), $whereParams));
    }

    public function getEnumValues($table, $column)
    {
        $stmt = $this->pdo->query("SHOW COLUMNS FROM `$table` WHERE Field = '$column'");
        $row = $stmt->fetch();
        if (!$row || strpos($row['Type'], 'enum') === false) return [];

        $enumStr = $row['Type'];
        $enumStr = str_replace(["enum(", ")"], "", $enumStr);
        $enumStr = str_getcsv($enumStr, ",", "'");

        return $enumStr;
    }
}
