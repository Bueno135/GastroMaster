<?php
/**
 * Página Principal - Dashboard
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/config/config.php';

// Verifica se está logado
requireLogin();

$user = getCurrentUser();
$pdo = getConnection();

// Busca receitas do usuário
$receitas = [];
$total_receitas = 0;

if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM receitas WHERE usuario_id = ? ORDER BY data_cadastro DESC LIMIT 6");
        $stmt->execute([$_SESSION['user_id']]);
        $receitas = $stmt->fetchAll();
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM receitas WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $total_receitas = $stmt->fetch()['total'];
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
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container">
        <div class="dashboard-header">
            <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome']); ?>! 👨‍🍳</h1>
            <p>Gerencie suas receitas gastronômicas de forma simples e prática.</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Receitas</h3>
                <p class="stat-number"><?php echo $total_receitas; ?></p>
            </div>
            <div class="stat-card">
                <h3>Últimas Receitas</h3>
                <p class="stat-number"><?php echo count($receitas); ?></p>
            </div>
        </div>
        
        <div class="section-header">
            <h2>Últimas Receitas Cadastradas</h2>
            <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Ver Todas</a>
        </div>
        
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
                            <div class="receita-actions">
                                <a href="<?php echo SITE_URL; ?>/receitas/ver.php?id=<?php echo $receita['id']; ?>" 
                                   class="btn btn-sm btn-primary">Ver</a>
                                <a href="<?php echo SITE_URL; ?>/receitas/editar.php?id=<?php echo $receita['id']; ?>" 
                                   class="btn btn-sm btn-secondary">Editar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>

