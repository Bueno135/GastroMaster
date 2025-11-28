<?php

require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/index.php');
    exit();
}

$erro = '';
$sucesso = '';

// Processa formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $pdo = getConnection();
        if (!$pdo) {
            $erro = 'Erro de conexão com o banco de dados.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch();
                
                // Verifica senha e cria sessão
                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_nome'] = $usuario['nome'];
                    $_SESSION['user_email'] = $usuario['email'];
                    
                    header('Location: ' . SITE_URL . '/index.php');
                    exit();
                } else {
                    $erro = 'Email ou senha inválidos.';
                }
            } catch (PDOException $e) {
                error_log("Erro no login: " . $e->getMessage());
                $erro = 'Erro ao processar login. Tente novamente.';
            }
        }
    }
}

if (isset($_GET['registered'])) {
    $sucesso = 'Registro realizado com sucesso! Faça login para continuar.';
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
                
                <?php if ($erro): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>
                
                <?php if ($sucesso): ?>
                    <div class="alert alert-success"><?php echo $sucesso; ?></div>
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
