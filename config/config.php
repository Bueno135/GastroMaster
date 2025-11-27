<?php
/**
 * Arquivo de configurações gerais do sistema
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do sistema
define('SITE_NAME', 'GastroMaster');
define('SITE_URL', 'http://localhost/Atividade_Final');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Tipos de arquivo permitidos para upload
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Inclui arquivo de conexão com banco
require_once __DIR__ . '/database.php';

/**
 * Verifica se o usuário está logado
 * @return bool True se estiver logado, False caso contrário
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Redireciona para página de login se não estiver autenticado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/auth/login.php');
        exit();
    }
}

/**
 * Obtém informações do usuário logado
 * @return array|null Array com dados do usuário ou null
 */
function getCurrentUser() {
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

/**
 * Sanitiza dados de entrada
 * @param string $data Dado a ser sanitizado
 * @return string Dado sanitizado
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida e processa upload de imagem
 * @param array $file Array $_FILES
 * @param string $currentImage Nome da imagem atual (para atualização)
 * @return array ['success' => bool, 'message' => string, 'filename' => string]
 */
function handleImageUpload($file, $currentImage = null) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Parâmetros de upload inválidos.'];
    }
    
    // Se não houver arquivo e houver imagem atual, mantém a atual
    if ($file['error'] === UPLOAD_ERR_NO_FILE && $currentImage) {
        return ['success' => true, 'message' => '', 'filename' => $currentImage];
    }
    
    // Se não houver arquivo e não houver imagem atual, retorna erro
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'Por favor, selecione uma imagem.'];
    }
    
    // Verifica erros de upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erro ao fazer upload do arquivo.'];
    }
    
    // Verifica tamanho do arquivo
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Arquivo muito grande. Tamanho máximo: 5MB.'];
    }
    
    // Verifica tipo do arquivo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use JPG, PNG ou GIF.'];
    }
    
    // Cria diretório de upload se não existir
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Gera nome único para o arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('receita_', true) . '.' . $extension;
    $filepath = UPLOAD_DIR . $filename;
    
    // Move o arquivo
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'message' => 'Erro ao salvar o arquivo.'];
    }
    
    // Remove imagem anterior se existir
    if ($currentImage && file_exists(UPLOAD_DIR . $currentImage)) {
        unlink(UPLOAD_DIR . $currentImage);
    }
    
    return ['success' => true, 'message' => 'Upload realizado com sucesso.', 'filename' => $filename];
}

