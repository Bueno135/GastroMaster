<?php
/**
 * Configuração de conexão com o banco de dados
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'gastromaster');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Estabelece conexão com o banco de dados usando PDO
 * @return PDO|null Retorna a conexão PDO ou null em caso de erro
 */
function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Erro de conexão: " . $e->getMessage());
        return null;
    }
}

