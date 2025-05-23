<?php
$titulo = 'Clientes - Sistema de Chamados';
require_once 'views/layouts/header.php';
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Clientes</h2>
        <a href="index.php?route=clientes/criar" class="btn btn-primary">
            <i class="bi bi-plus"></i> Novo Cliente
        </a>
    </div>

    <div class="row">
        <?php foreach ($clientes as $cliente): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($cliente['nome']); ?></h5>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?route=chamados&cliente_id=<?php echo $cliente['id']; ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-ticket"></i> Ver Chamados
                        </a>
                        <div>
                            <a href="index.php?route=portal/acessar&id=<?php echo $cliente['id']; ?>" 
                               class="btn btn-success btn-sm" title="Acessar Portal" target="_blank">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <button type="button" class="btn btn-info btn-sm" title="Credenciais Portal" 
                                    data-bs-toggle="modal" data-bs-target="#credenciaisModal<?php echo $cliente['id']; ?>">
                                <i class="bi bi-key"></i>
                            </button>
                            <a href="index.php?route=clientes/editar&id=<?php echo $cliente['id']; ?>" 
                               class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="index.php?route=clientes/excluir&id=<?php echo $cliente['id']; ?>" 
                               class="btn btn-danger btn-sm" title="Excluir"
                               onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Credenciais -->
            <div class="modal fade" id="credenciaisModal<?php echo $cliente['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Credenciais do Portal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($cliente['email_portal']); ?>" readonly>
                                    <button class="btn btn-outline-secondary copy-btn" data-copy="<?php echo htmlspecialchars($cliente['email_portal']); ?>">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Senha:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control senha-input" value="<?php echo htmlspecialchars($cliente['senha_portal']); ?>" readonly>
                                    <button class="btn btn-outline-secondary toggle-senha">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary copy-btn" data-copy="<?php echo htmlspecialchars($cliente['senha_portal']); ?>">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para copiar texto
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Feedback visual
            const btn = event.target.closest('.copy-btn');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 2000);
        }).catch(function(err) {
            console.error('Erro ao copiar:', err);
        });
    }

    // Adicionar eventos de clique para os botões de copiar
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            copyToClipboard(textToCopy);
        });
    });

    // Adicionar eventos de clique para os botões de mostrar/ocultar senha
    document.querySelectorAll('.toggle-senha').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.senha-input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?> 