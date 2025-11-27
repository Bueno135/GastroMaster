<?php
/**
 * Página de Exclusão de Receitas
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/../config/config.php';

// Verifica se está logado
requireLogin();

$id = $_GET['id'] ?? 0;
$error = '';
$success = '';

if ($id) {
    $pdo = getConnection();
    if ($pdo) {
        try {
            // Busca a receita para pegar o nome da imagem
            $stmt = $pdo->prepare("SELECT imagem FROM receitas WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $_SESSION['user_id']]);
            $receita = $stmt->fetch();
            
            if ($receita) {
                // Exclui a receita (a imagem será excluída pela foreign key CASCADE ou manualmente)
                $stmt = $pdo->prepare("DELETE FROM receitas WHERE id = ? AND usuario_id = ?");
                $stmt->execute([$id, $_SESSION['user_id']]);
                
                // Remove a imagem se existir
                if ($receita['imagem'] && file_exists(UPLOAD_DIR . $receita['imagem'])) {
                    unlink(UPLOAD_DIR . $receita['imagem']);
                }
                
                $success = 'Receita excluída com sucesso!';
            } else {
                $error = 'Receita não encontrada.';
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir receita: " . $e->getMessage());
            $error = 'Erro ao excluir receita. Tente novamente.';
        }
    } else {
        $error = 'Erro de conexão com o banco de dados.';
    }
} else {
    $error = 'ID da receita não informado.';
}

// Redireciona após exclusão
if ($success || $error) {
    header('Location: ' . SITE_URL . '/receitas/listar.php?msg=' . urlencode($success ?: $error));
    exit();
}
?>

