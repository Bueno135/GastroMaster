<?php

require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    header('Location: ' . SITE_URL . '/index.php');
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $pdo = getConnection();
        if (!$pdo) {
            $erro = 'Erro de conexão com o banco de dados.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $erro = 'Este email já está cadastrado.';
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                    $stmt->execute([$nome, $email, $senha_hash]);
                    
                    header('Location: ' . SITE_URL . '/auth/login.php?registered=1');
                    exit();
                }
            } catch (PDOException $e) {
                error_log("Erro no registro: " . $e->getMessage());
                $erro = 'Erro ao processar registro. Tente novamente.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-box">
                <h1 class="auth-title">🍳 <?php echo SITE_NAME; ?></h1>
                <p class="auth-subtitle">Crie sua conta para começar</p>
                
                <?php if ($erro): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label for="nome">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" required 
                               placeholder="Seu nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="seu@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required 
                               placeholder="Mínimo 6 caracteres" minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Senha:</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                               placeholder="Digite a senha novamente" minlength="6">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                </form>
                
                <div class="auth-footer">
                    <p>Já tem uma conta? <a href="<?php echo SITE_URL; ?>/auth/login.php">Faça login</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>
