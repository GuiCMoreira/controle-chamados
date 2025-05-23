<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página não encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1 class="display-1">404</h1>
        <h2 class="mb-4">Página não encontrada</h2>
        <p class="mb-4">A página que você está procurando não existe ou foi movida.</p>
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Voltar para a página inicial</a>
    </div>
</body>
</html> 