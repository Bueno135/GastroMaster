<?php
/**
 * Página de Login
 * GastroMaster - Sistema de Gerenciamento de Receitas
 */

require_once __DIR__ . '/../config/config.php';

// Se já estiver logado, redireciona para o painel
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/index.php');
    exit();
}

$error = '';
$success = '';

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        $pdo = getConnection();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($senha, $user['senha'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nome'] = $user['nome'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    header('Location: ' . SITE_URL . '/index.php');
                    exit();
                } else {
                    $error = 'Email ou senha inválidos.';
                }
            } catch (PDOException $e) {
                error_log("Erro no login: " . $e->getMessage());
                $error = 'Erro ao processar login. Tente novamente.';
            }
        } else {
            $error = 'Erro de conexão com o banco de dados.';
        }
    }
}

// Verifica se há mensagem de sucesso (após registro)
if (isset($_GET['registered'])) {
    $success = 'Registro realizado com sucesso! Faça login para continuar.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h1 class="auth-title">🍳 <?php echo SITE_NAME; ?></h1>
                <p class="auth-subtitle">Faça login para gerenciar suas receitas</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="auth-form" id="loginForm">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="seu@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required 
                               placeholder="Sua senha">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>
                
                <div class="auth-footer">
                    <p>Não tem uma conta? <a href="<?php echo SITE_URL; ?>/auth/register.php">Cadastre-se</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>

