<?php
require_once 'models/Cliente.php';
require_once 'config/database.php';

class ClienteController {
    private $db;
    private $cliente;

    public function __construct($db) {
        $this->db = $db;
        require_once 'models/Cliente.php';
        $this->cliente = new Cliente($db);
    }

    public function index() {
        $clientes = $this->cliente->read();
        require_once 'views/clientes/index.php';
    }

    public function criar() {
        $cliente = null;
        require_once 'views/clientes/form.php';
    }

    public function editar() {
        if (isset($_GET['id'])) {
            $this->cliente->id = $_GET['id'];
            $cliente = $this->cliente->readOne();

            if (!$cliente) {
                $_SESSION['error'] = "Cliente não encontrado.";
                header('Location: index.php?route=clientes');
                exit;
            }

            // Garante que todas as propriedades necessárias existam
            $cliente['nome'] = $cliente['nome'] ?? '';
            $cliente['email_portal'] = $cliente['email_portal'] ?? '';
            $cliente['senha_portal'] = $cliente['senha_portal'] ?? '';

            require_once 'views/clientes/form.php';
        } else {
            $_SESSION['error'] = "ID do cliente não fornecido.";
            header('Location: index.php?route=clientes');
            exit;
        }
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->cliente->nome = $_POST['nome'] ?? '';
            $this->cliente->email_portal = $_POST['email_portal'] ?? '';
            $this->cliente->senha_portal = $_POST['senha_portal'] ?? '';

            if (isset($_POST['id'])) {
                $this->cliente->id = $_POST['id'];
                if ($this->cliente->update()) {
                    $_SESSION['success'] = "Cliente atualizado com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao atualizar cliente.";
                }
            } else {
                if ($this->cliente->create()) {
                    $_SESSION['success'] = "Cliente criado com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao criar cliente.";
                }
            }
            header('Location: index.php?route=clientes');
            exit;
        }
    }

    public function excluir() {
        if (isset($_GET['id'])) {
            $this->cliente->id = $_GET['id'];
            if (!$this->cliente->delete()) {
                $_SESSION['error'] = "Não é possível excluir este cliente pois existem chamados vinculados a ele.";
            } else {
                $_SESSION['success'] = "Cliente excluído com sucesso.";
            }
        }
        header('Location: index.php?route=clientes');
        exit;
    }
} 