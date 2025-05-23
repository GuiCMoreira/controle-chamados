<?php
// Define a URL base do sistema
define('BASE_URL', getenv('APP_URL') ?: 'http://localhost/PROJETOS/projeto-chamados');
define('APP_NAME', 'Sistema de Chamados');

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de upload
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Funções auxiliares
function redirect($path) {
    header('Location: ' . BASE_URL . '/' . $path);
    exit;
} 