<?php
class Chamado {
    private $conn;
    private $table_name = "chamados";
    private $db;

    public $id;
    public $cliente_id;
    public $numero_chamado;
    public $titulo;
    public $status;
    public $created_at;
    public $updated_at;
    public $ultima_verificacao;
    public $status_execucao;

    public function __construct($db) {
        $this->conn = $db;
        $this->db = Database::getInstance();
    }

    public function read() {
        $cache_key = 'chamados_all';
        $cached_data = $this->db->getCache($cache_key);
        
        if ($cached_data !== null) {
            return $cached_data;
        }

        $query = "SELECT c.*, cl.nome as cliente_nome 
                 FROM " . $this->table_name . " c
                 LEFT JOIN clientes cl ON c.cliente_id = cl.id
                 ORDER BY 
                    CASE c.status
                        WHEN 'Aberto' THEN 1
                        WHEN 'Pendente Cliente' THEN 2
                        WHEN 'Aguardando Validação' THEN 3
                        WHEN 'Resolvido' THEN 4
                    END,
                    c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $this->db->setCache($cache_key, $result);
        return $result;
    }

    public function readByCliente($cliente_id) {
        $cache_key = 'chamados_cliente_' . $cliente_id;
        $cached_data = $this->db->getCache($cache_key);
        
        if ($cached_data !== null) {
            return $cached_data;
        }

        $query = "SELECT c.*, cl.nome as cliente_nome 
                 FROM " . $this->table_name . " c
                 LEFT JOIN clientes cl ON c.cliente_id = cl.id
                 WHERE c.cliente_id = ?
                 ORDER BY 
                    CASE c.status
                        WHEN 'Aberto' THEN 1
                        WHEN 'Pendente Cliente' THEN 2
                        WHEN 'Aguardando Validação' THEN 3
                        WHEN 'Resolvido' THEN 4
                    END,
                    c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cliente_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        $this->db->setCache($cache_key, $result);
        return $result;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (cliente_id, numero_chamado, titulo, status, created_at, status_execucao)
                VALUES
                (:cliente_id, :numero_chamado, :titulo, :status, :created_at, :status_execucao)
                RETURNING id";

        $stmt = $this->conn->prepare($query);

        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":numero_chamado", $this->numero_chamado);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":status_execucao", $this->status_execucao);

        if($stmt->execute()) {
            $this->db->clearCache('chamados_all');
            $this->db->clearCache('chamados_cliente_' . $this->cliente_id);
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    cliente_id = :cliente_id,
                    numero_chamado = :numero_chamado,
                    titulo = :titulo,
                    status = :status,
                    updated_at = :updated_at,
                    status_execucao = :status_execucao
                WHERE
                    id = :id
                RETURNING id";

        $stmt = $this->conn->prepare($query);

        $this->updated_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":numero_chamado", $this->numero_chamado);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":status_execucao", $this->status_execucao);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            $this->db->clearCache('chamados_all');
            $this->db->clearCache('chamados_cliente_' . $this->cliente_id);
            return true;
        }
        return false;
    }

    public function registrarVerificacao() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    ultima_verificacao = :ultima_verificacao
                WHERE
                    id = :id
                RETURNING id";

        $stmt = $this->conn->prepare($query);

        $this->ultima_verificacao = date('Y-m-d H:i:s');

        $stmt->bindParam(":ultima_verificacao", $this->ultima_verificacao);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            $this->db->clearCache('chamados_all');
            $this->db->clearCache('chamados_cliente_' . $this->cliente_id);
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ? RETURNING cliente_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            $cliente_id = $stmt->fetch(PDO::FETCH_ASSOC)['cliente_id'];
            $this->db->clearCache('chamados_all');
            $this->db->clearCache('chamados_cliente_' . $cliente_id);
            return true;
        }
        return false;
    }

    public function readOne() {
        $cache_key = 'chamado_' . $this->id;
        $cached_data = $this->db->getCache($cache_key);
        
        if ($cached_data !== null) {
            return $cached_data;
        }

        $query = "SELECT c.*, cl.nome as cliente_nome 
                 FROM " . $this->table_name . " c
                 LEFT JOIN clientes cl ON c.cliente_id = cl.id
                 WHERE c.id = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->db->setCache($cache_key, $result);
        return $result;
    }
} 