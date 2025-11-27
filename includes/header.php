<?php
/**
 * Header do Sistema
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

if (!isLoggedIn()) {
    return;
}

$user = getCurrentUser();
?>

<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="<?php echo SITE_URL; ?>/index.php">
                    <h1>🍳 <?php echo SITE_NAME; ?></h1>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php">Dashboard</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/receitas/listar.php">Receitas</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/receitas/cadastrar.php">Nova Receita</a></li>
                    <li class="user-menu">
                        <span>Olá, <?php echo htmlspecialchars($user['nome']); ?></span>
                        <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="btn btn-sm btn-danger">Sair</a>
                    </li>
                </ul>
            </nav>
            
            <button class="mobile-menu-toggle" id="mobileMenuToggle">☰</button>
        </div>
    </div>
</header>

<nav class="mobile-nav" id="mobileNav">
    <ul>
        <li><a href="<?php echo SITE_URL; ?>/index.php">Dashboard</a></li>
        <li><a href="<?php echo SITE_URL; ?>/receitas/listar.php">Receitas</a></li>
        <li><a href="<?php echo SITE_URL; ?>/receitas/cadastrar.php">Nova Receita</a></li>
        <li><a href="<?php echo SITE_URL; ?>/auth/logout.php">Sair</a></li>
    </ul>
</nav>

