<?php
/**
 * Página de Logout
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/../config/config.php';

// Destroi a sessão
session_destroy();

// Redireciona para a página de login
header('Location: ' . SITE_URL . '/auth/login.php');
exit();

