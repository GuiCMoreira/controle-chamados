<?php
require_once 'models/Chamado.php';
require_once 'models/Cliente.php';
require_once 'config/database.php';

class ChamadoController {
    private $db;
    private $chamado;
    private $cliente;

    public function __construct($db) {
        $this->db = $db;
        $this->chamado = new Chamado($db);
        $this->cliente = new Cliente($db);
    }

    public function index() {
        $cliente_id = $_GET['cliente_id'] ?? null;
        
        if ($cliente_id) {
            $chamados = $this->chamado->readByCliente($cliente_id);
        } else {
            $chamados = $this->chamado->read();
        }
        
        // Buscar todos os clientes para o menu de abas
        $clientes = $this->cliente->read();
        
        require_once 'views/chamados/index.php';
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->chamado->cliente_id = $_POST['cliente_id'];
            $this->chamado->numero_chamado = $_POST['numero_chamado'];
            $this->chamado->titulo = $_POST['titulo'];
            $this->chamado->status = $_POST['status'];
            $this->chamado->status_execucao = $_POST['status_execucao'];

            if ($this->chamado->create()) {
                header('Location: index.php?route=chamados&cliente_id=' . $_POST['cliente_id']);
                exit;
            }
        }
        
        // Buscar todos os clientes para o select
        $clientes = $this->cliente->read();

        // Se vier da página de chamados de um cliente específico, pré-selecionar o cliente
        $cliente_id = $_GET['cliente_id'] ?? null;
        
        require_once 'views/chamados/form.php';
    }

    public function editar() {
        if(!isset($_GET['id'])) {
            header('Location: index.php?route=chamados');
            exit;
        }

        $this->chamado->id = $_GET['id'];
        $chamado = $this->chamado->readOne();

        if (!$chamado) {
            header('Location: index.php?route=chamados');
            exit;
        }

        // Buscar todos os clientes para o select
        $clientes = $this->cliente->read();
        
        require_once 'views/chamados/form.php';
    }

    public function salvar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->chamado->cliente_id = $_POST['cliente_id'];
            $this->chamado->numero_chamado = $_POST['numero_chamado'];
            $this->chamado->titulo = $_POST['titulo'];
            $this->chamado->status = $_POST['status'];
            $this->chamado->status_execucao = $_POST['status_execucao'];

            if(isset($_POST['id'])) {
                $this->chamado->id = $_POST['id'];
                $this->chamado->update();
            } else {
                $this->chamado->create();
            }

            header('Location: index.php?route=chamados');
            exit;
        }
    }

    public function verificar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $this->chamado->id = $_GET['id'];
            $result = $this->chamado->registrarVerificacao();
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit;
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function excluir() {
        if(isset($_GET['id'])) {
            $this->chamado->id = $_GET['id'];
            
            // Buscar o cliente_id do chamado antes de excluir
            $chamado = $this->chamado->readOne();
            $cliente_id = $chamado['cliente_id'];
            
            $this->chamado->delete();
            
            // Redirecionar para a página de chamados do cliente específico
            header('Location: index.php?route=chamados&cliente_id=' . $cliente_id);
            exit;
        }
        header('Location: index.php?route=chamados');
        exit;
    }
} 