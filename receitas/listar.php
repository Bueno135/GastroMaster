<?php
/**
 * Página de Listagem de Receitas
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/../config/config.php';

// Verifica se está logado
requireLogin();

$pdo = getConnection();
$receitas = [];
$message = '';

// Verifica se há mensagem de sucesso/erro
if (isset($_GET['msg'])) {
    $message = urldecode($_GET['msg']);
}

// Busca receitas do usuário
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM receitas WHERE usuario_id = ? ORDER BY data_cadastro DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $receitas = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao buscar receitas: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Receitas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Minhas Receitas</h1>
            <a href="<?php echo SITE_URL; ?>/receitas/cadastrar.php" class="btn btn-primary">Nova Receita</a>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (empty($receitas)): ?>
            <div class="empty-state">
                <p>Você ainda não cadastrou nenhuma receita.</p>
                <a href="<?php echo SITE_URL; ?>/receitas/cadastrar.php" class="btn btn-primary">Cadastrar Primeira Receita</a>
            </div>
        <?php else: ?>
            <div class="receitas-grid">
                <?php foreach ($receitas as $receita): ?>
                    <div class="receita-card">
                        <?php if ($receita['imagem']): ?>
                            <img src="<?php echo UPLOAD_URL . $receita['imagem']; ?>" 
                                 alt="<?php echo htmlspecialchars($receita['nome']); ?>" 
                                 class="receita-image">
                        <?php else: ?>
                            <div class="receita-image-placeholder">🍳</div>
                        <?php endif; ?>
                        
                        <div class="receita-content">
                            <h3><?php echo htmlspecialchars($receita['nome']); ?></h3>
                            <p class="receita-category"><?php echo htmlspecialchars($receita['categoria']); ?></p>
                            <div class="receita-meta">
                                <span>⏱️ <?php echo htmlspecialchars($receita['tempo_preparo']); ?></span>
                                <span>📊 <?php echo htmlspecialchars($receita['nivel_dificuldade']); ?></span>
                            </div>
                            <p class="receita-excerpt">
                                <?php 
                                $ingredientes = substr($receita['ingredientes'], 0, 100);
                                echo htmlspecialchars($ingredientes);
                                echo strlen($receita['ingredientes']) > 100 ? '...' : '';
                                ?>
                            </p>
                            <div class="receita-actions">
                                <a href="<?php echo SITE_URL; ?>/receitas/ver.php?id=<?php echo $receita['id']; ?>" 
                                   class="btn btn-sm btn-primary">Ver</a>
                                <a href="<?php echo SITE_URL; ?>/receitas/editar.php?id=<?php echo $receita['id']; ?>" 
                                   class="btn btn-sm btn-secondary">Editar</a>
                                <a href="<?php echo SITE_URL; ?>/receitas/excluir.php?id=<?php echo $receita['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir esta receita?');">Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

