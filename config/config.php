<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'GastroMaster');
define('SITE_URL', 'http://localhost/Atividade_Final');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

require_once __DIR__ . '/database.php';

// Verifica se usuário está logado
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Redireciona para login se não estiver autenticado
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/auth/login.php');
        exit();
    }
}

// Retorna dados do usuário atual
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }
    
    $pdo = getConnection();
    if (!$pdo) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Erro ao buscar usuário: " . $e->getMessage());
        return null;
    }
}

// Limpa e sanitiza dados de entrada
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
