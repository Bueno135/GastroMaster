<?php
/**
 * Página de Visualização de Receita
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/../config/config.php';

// Verifica se está logado
requireLogin();

$id = $_GET['id'] ?? 0;
$receita = null;
$error = '';

if ($id) {
    $pdo = getConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM receitas WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $_SESSION['user_id']]);
            $receita = $stmt->fetch();
            
            if (!$receita) {
                $error = 'Receita não encontrada.';
            }
        } catch (PDOException $e) {
            error_log("Erro ao buscar receita: " . $e->getMessage());
            $error = 'Erro ao carregar receita.';
        }
    }
} else {
    $error = 'ID da receita não informado.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $receita ? htmlspecialchars($receita['nome']) : 'Receita'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container">
        <?php if ($error || !$receita): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Voltar</a>
        <?php else: ?>
            <div class="page-header">
                <div>
                    <h1><?php echo htmlspecialchars($receita['nome']); ?></h1>
                    <p class="receita-meta-large">
                        <span>📁 <?php echo htmlspecialchars($receita['categoria']); ?></span>
                        <span>⏱️ <?php echo htmlspecialchars($receita['tempo_preparo']); ?></span>
                        <span>📊 <?php echo htmlspecialchars($receita['nivel_dificuldade']); ?></span>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="<?php echo SITE_URL; ?>/receitas/editar.php?id=<?php echo $receita['id']; ?>" 
                       class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
            
            <div class="receita-detail">
                <?php if ($receita['imagem']): ?>
                    <div class="receita-image-large">
                        <img src="<?php echo UPLOAD_URL . $receita['imagem']; ?>" 
                             alt="<?php echo htmlspecialchars($receita['nome']); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="receita-section">
                    <h2>📋 Ingredientes</h2>
                    <div class="receita-content-text">
                        <?php echo nl2br(htmlspecialchars($receita['ingredientes'])); ?>
                    </div>
                </div>
                
                <div class="receita-section">
                    <h2>👨‍🍳 Modo de Preparo</h2>
                    <div class="receita-content-text">
                        <?php echo nl2br(htmlspecialchars($receita['modo_preparo'])); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

