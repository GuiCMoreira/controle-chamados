<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'controllers/ChamadoController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/PortalController.php';

$db = new Database();
$db = $db->getConnection();

$route = $_GET['route'] ?? 'chamados';

// Definir a rota atual para o menu
$current_route = explode('/', $route)[0];

switch ($route) {
    case '':
    case 'chamados':
        $controller = new ChamadoController($db);
        $controller->index();
        break;

    case 'portal/acessar':
        $controller = new PortalController($db);
        $controller->acessar();
        break;

    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'chamados/criar':
        $controller = new ChamadoController($db);
        $controller->criar();
        break;

    case 'chamados/editar':
        $controller = new ChamadoController($db);
        $controller->editar();
        break;

    case 'chamados/salvar':
        $controller = new ChamadoController($db);
        $controller->salvar();
        break;

    case 'chamados/verificar':
        $controller = new ChamadoController($db);
        $controller->verificar();
        break;

    case 'chamados/excluir':
        $controller = new ChamadoController($db);
        $controller->excluir();
        break;

    case 'clientes':
        $controller = new ClienteController($db);
        $controller->index();
        break;

    case 'clientes/criar':
        $controller = new ClienteController($db);
        $controller->criar();
        break;

    case 'clientes/editar':
        $controller = new ClienteController($db);
        $controller->editar();
        break;

    case 'clientes/salvar':
        $controller = new ClienteController($db);
        $controller->salvar();
        break;

    case 'clientes/excluir':
        $controller = new ClienteController($db);
        $controller->excluir();
        break;

    default:
        redirect('index.php?route=clientes');
        break;
} 