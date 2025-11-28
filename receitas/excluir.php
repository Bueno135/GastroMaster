<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/ReceitaRepository.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$erro = '';
$sucesso = '';

// Exclui receita e imagem associada
if (!$id) {
    $erro = 'ID da receita não informado.';
} else {
    $repositorio = new ReceitaRepository();
    $imagem = $repositorio->delete($id, $_SESSION['user_id']);
    
    if ($imagem === null) {
        $erro = 'Receita não encontrada.';
    } elseif ($imagem === false) {
        $erro = 'Erro ao excluir receita. Tente novamente.';
    } else {
        // Remove arquivo de imagem do servidor
        if ($imagem && file_exists(UPLOAD_DIR . $imagem)) {
            unlink(UPLOAD_DIR . $imagem);
        }
        $sucesso = 'Receita excluída com sucesso!';
    }
}

$mensagem = $sucesso ?: $erro;
header('Location: ' . SITE_URL . '/receitas/listar.php?msg=' . urlencode($mensagem));
exit();
