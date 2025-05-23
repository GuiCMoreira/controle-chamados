<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($chamado) ? 'Editar' : 'Novo'; ?> Chamado - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?route=clientes"><?php echo APP_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?route=clientes">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?route=clientes/criar">Novo Cliente</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1><?php echo isset($chamado) ? 'Editar' : 'Novo'; ?> Chamado</h1>
                <form action="index.php?route=chamados/salvar" method="POST">
                    <?php if (isset($chamado)): ?>
                        <input type="hidden" name="id" value="<?php echo $chamado['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="numero_chamado" class="form-label">Número do Chamado</label>
                        <input type="text" class="form-control" id="numero_chamado" name="numero_chamado" value="<?php echo $chamado['numero_chamado'] ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="">Selecione um cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" <?php echo (isset($chamado) && $chamado['cliente_id'] == $cliente['id']) || (isset($cliente_id) && $cliente_id == $cliente['id']) ? 'selected' : ''; ?>>
                                    <?php echo $cliente['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $chamado['titulo'] ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Aberto" <?php echo (isset($chamado) && $chamado['status'] == 'Aberto') ? 'selected' : ''; ?>>Aberto</option>
                            <option value="Pendente Cliente" <?php echo (isset($chamado) && $chamado['status'] == 'Pendente Cliente') ? 'selected' : ''; ?>>Pendente Cliente</option>
                            <option value="Aguardando Validação" <?php echo (isset($chamado) && $chamado['status'] == 'Aguardando Validação') ? 'selected' : ''; ?>>Aguardando Validação</option>
                            <option value="Resolvido" <?php echo (isset($chamado) && $chamado['status'] == 'Resolvido') ? 'selected' : ''; ?>>Resolvido</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status_execucao" class="form-label">Status de Execução</label>
                        <textarea class="form-control" id="status_execucao" name="status_execucao" rows="3"><?php echo $chamado['status_execucao'] ?? ''; ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php?route=chamados" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 