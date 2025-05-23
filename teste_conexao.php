<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if ($conn) {
        echo "Conexão com o banco de dados estabelecida com sucesso!";
    }
} catch (Exception $e) {
    echo "Erro na conexão: " . $e->getMessage();
}