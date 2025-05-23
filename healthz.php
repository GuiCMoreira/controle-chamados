<?php
header('Content-Type: application/json');

try {
    // Tenta conectar ao banco de dados
    $db = new PDO(
        "pgsql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS')
    );
    
    echo json_encode([
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'unhealthy',
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} 