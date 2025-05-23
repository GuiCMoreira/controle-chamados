<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";
    private $db;

    public $id;
    public $nome;
    public $data_cadastro;
    public $data_atualizacao;
    public $email_portal;
    public $senha_portal;

    public function __construct($db) {
        $this->conn = $db;
        $this->db = Database::getInstance();
    }

    public function read() {
        $cache_key = 'clientes_all';
        $cached_data = $this->db->getCache($cache_key);
        
        if ($cached_data !== null) {
            return $cached_data;
        }

        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $this->db->setCache($cache_key, $result);
        return $result;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (nome, email_portal, senha_portal) 
                 VALUES 
                 (:nome, :email_portal, :senha_portal)
                 RETURNING id";
        
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email_portal = htmlspecialchars(strip_tags($this->email_portal));
        $this->senha_portal = htmlspecialchars(strip_tags($this->senha_portal));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email_portal", $this->email_portal);
        $stmt->bindParam(":senha_portal", $this->senha_portal);

        if($stmt->execute()) {
            $this->db->clearCache('clientes_all');
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nome = :nome, 
                     email_portal = :email_portal, 
                     senha_portal = :senha_portal 
                 WHERE id = :id
                 RETURNING id";
        
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email_portal = htmlspecialchars(strip_tags($this->email_portal));
        $this->senha_portal = htmlspecialchars(strip_tags($this->senha_portal));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email_portal", $this->email_portal);
        $stmt->bindParam(":senha_portal", $this->senha_portal);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            $this->db->clearCache('clientes_all');
            $this->db->clearCache('cliente_' . $this->id);
            return true;
        }
        return false;
    }

    public function delete() {
        // Primeiro verifica se existem chamados vinculados
        $query = "SELECT COUNT(*) as total FROM chamados WHERE cliente_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? RETURNING id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            $this->db->clearCache('clientes_all');
            $this->db->clearCache('cliente_' . $this->id);
            return true;
        }
        return false;
    }

    public function readOne() {
        $cache_key = 'cliente_' . $this->id;
        $cached_data = $this->db->getCache($cache_key);
        
        if ($cached_data !== null) {
            return $cached_data;
        }

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->db->setCache($cache_key, $result);
        return $result;
    }
} 