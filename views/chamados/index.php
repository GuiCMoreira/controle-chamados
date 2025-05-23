<?php
$titulo = 'Chamados - Sistema de Chamados';
require_once 'views/layouts/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chamados</h1>
        <a href="index.php?route=chamados/criar<?php echo isset($_GET['cliente_id']) ? '&cliente_id=' . $_GET['cliente_id'] : ''; ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Chamado
        </a>
    </div>

    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <h5 class="me-3 mb-0">Filtrar por Cliente:</h5>
            <a href="index.php?route=chamados" class="btn btn-outline-secondary me-2 <?php echo !isset($_GET['cliente_id']) ? 'active' : ''; ?>">
                Todos
            </a>
            <?php foreach ($clientes as $cliente): ?>
                <a href="index.php?route=chamados&cliente_id=<?php echo $cliente['id']; ?>" 
                   class="btn btn-outline-primary me-2 <?php echo (isset($_GET['cliente_id']) && $_GET['cliente_id'] == $cliente['id']) ? 'active' : ''; ?>">
                    <?php echo $cliente['nome']; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if (isset($_GET['cliente_id'])): ?>
            <?php 
            $cliente_atual = array_filter($clientes, function($c) {
                return $c['id'] == $_GET['cliente_id'];
            });
            $cliente_atual = reset($cliente_atual);
            ?>
            <h4 class="text-primary">Chamados de: <?php echo $cliente_atual['nome']; ?></h4>
        <?php else: ?>
            <h4 class="text-primary">Todos os Chamados</h4>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Título</th>
                    <th>Data de Abertura</th>
                    <th>Status</th>
                    <th>Última Verificação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chamados as $chamado): ?>
                <tr class="chamado-row" style="cursor:pointer;" data-status_execucao="<?php echo htmlspecialchars($chamado['status_execucao']); ?>" data-numero="<?php echo htmlspecialchars($chamado['numero_chamado']); ?>" data-titulo="<?php echo htmlspecialchars($chamado['titulo']); ?>">
                    <td><?php echo $chamado['numero_chamado']; ?></td>
                    <td><?php echo $chamado['cliente_nome']; ?></td>
                    <td><?php echo $chamado['titulo']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($chamado['created_at'])); ?></td>
                    <td>
                        <span class="badge bg-<?php 
                            echo match($chamado['status']) {
                                'Aberto' => 'danger',
                                'Pendente Cliente' => 'warning',
                                'Aguardando Validação' => 'info',
                                'Resolvido' => 'success',
                                default => 'secondary'
                            };
                        ?>">
                            <?php echo $chamado['status']; ?>
                        </span>
                    </td>
                    <td><?php echo $chamado['ultima_verificacao'] ? date('d/m/Y', strtotime($chamado['ultima_verificacao'])) : 'Nunca'; ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="index.php?route=chamados/editar&id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-success verificar-btn" data-id="<?php echo $chamado['id']; ?>">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            <a href="index.php?route=chamados/excluir&id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este chamado?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Status de Execução -->
<div class="modal fade" id="statusExecucaoModal" tabindex="-1" aria-labelledby="statusExecucaoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusExecucaoModalLabel">Status de Execução</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <strong id="modalTitulo"></strong>
        <p id="modalStatusExecucao" class="mt-2"></p>
      </div>
    </div>
  </div>
</div>

<script>
    document.querySelectorAll('.verificar-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch('index.php?route=chamados/verificar&id=' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });

    // Modal de Status de Execução
    document.querySelectorAll('.chamado-row').forEach(row => {
        row.addEventListener('click', function(e) {
            // Evita abrir o modal se clicar em um botão de ação
            if (e.target.closest('.btn-group')) return;
            const status = this.getAttribute('data-status_execucao') || 'Sem status de execução.';
            const numero = this.getAttribute('data-numero');
            const titulo = this.getAttribute('data-titulo');
            document.getElementById('modalTitulo').textContent = `Chamado #${numero} - ${titulo}`;
            document.getElementById('modalStatusExecucao').textContent = status;
            const modal = new bootstrap.Modal(document.getElementById('statusExecucaoModal'));
            modal.show();
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?> 