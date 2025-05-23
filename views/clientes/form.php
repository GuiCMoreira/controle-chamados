<?php
$titulo = isset($cliente) ? 'Editar Cliente' : 'Novo Cliente';
require_once 'views/layouts/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $titulo; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?route=clientes/salvar<?php echo isset($cliente) && isset($cliente['id']) ? '&id=' . $cliente['id'] : ''; ?>" method="POST">
                        <?php if (isset($cliente) && isset($cliente['id'])): ?>
                            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Cliente</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="<?php echo isset($cliente) && isset($cliente['nome']) ? htmlspecialchars($cliente['nome']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email_portal" class="form-label">Email do Portal TOTVS</label>
                            <input type="email" class="form-control" id="email_portal" name="email_portal" 
                                   value="<?php echo isset($cliente) && isset($cliente['email_portal']) ? htmlspecialchars($cliente['email_portal']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label for="senha_portal" class="form-label">Senha do Portal TOTVS</label>
                            <input type="password" class="form-control" id="senha_portal" name="senha_portal" 
                                   value="<?php echo isset($cliente) && isset($cliente['senha_portal']) ? htmlspecialchars($cliente['senha_portal']) : ''; ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?route=clientes" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 