<?php
class Database {
    private $host = "dpg-d0o61rbe5dus73b6n0s0-a.oregon-postgres.render.com";
    private $db_name = "sistemachamados";
    private $username = "gui";
    private $password = "3Od62TSrBFzh433fqv335rKifOFM1PGc";
    public $conn;
    private static $instance = null;
    private $cache = [];
    private $cache_time = 300; // 5 minutos

    public function __construct() {
        if (self::$instance === null) {
            self::$instance = $this;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "pgsql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => true // Mantém a conexão viva
                    ]
                );
            } catch(PDOException $e) {
                echo "Erro de conexão: " . $e->getMessage();
            }
        }
        return $this->conn;
    }

    public function getCache($key) {
        if (isset($this->cache[$key]) && (time() - $this->cache[$key]['time']) < $this->cache_time) {
            return $this->cache[$key]['data'];
        }
        return null;
    }

    public function setCache($key, $data) {
        $this->cache[$key] = [
            'data' => $data,
            'time' => time()
        ];
    }

    public function clearCache($key = null) {
        if ($key === null) {
            $this->cache = [];
        } else {
            unset($this->cache[$key]);
        }
    }
} 