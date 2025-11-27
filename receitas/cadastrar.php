<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/ReceitaRepository.php';
require_once __DIR__ . '/../services/ImageUploader.php';

requireLogin();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome'] ?? '');
    $categoria = sanitize($_POST['categoria'] ?? '');
    $ingredientes = sanitize($_POST['ingredientes'] ?? '');
    $modo_preparo = sanitize($_POST['modo_preparo'] ?? '');
    $tempo_preparo = sanitize($_POST['tempo_preparo'] ?? '');
    $nivel_dificuldade = sanitize($_POST['nivel_dificuldade'] ?? '');
    
    if (empty($nome) || empty($categoria) || empty($ingredientes) || 
        empty($modo_preparo) || empty($tempo_preparo) || empty($nivel_dificuldade)) {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    } else {
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($_FILES['imagem']);
            if (!$upload['success']) {
                $erro = $upload['message'];
            } else {
                $imagem = $upload['filename'];
            }
        }
        
        if (empty($erro)) {
            $repositorio = new ReceitaRepository();
            if ($repositorio->create([
                'usuario_id' => $_SESSION['user_id'],
                'nome' => $nome,
                'categoria' => $categoria,
                'ingredientes' => $ingredientes,
                'modo_preparo' => $modo_preparo,
                'tempo_preparo' => $tempo_preparo,
                'nivel_dificuldade' => $nivel_dificuldade,
                'imagem' => $imagem
            ])) {
                $sucesso = 'Receita cadastrada com sucesso!';
                $_POST = [];
            } else {
                $erro = 'Erro ao cadastrar receita. Tente novamente.';
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
    <title>Cadastrar Receita - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Cadastrar Nova Receita</h1>
            <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Voltar</a>
        </div>
        
        <?php if ($erro): ?>
            <div class="alert alert-error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <?php echo $sucesso; ?>
                <a href="<?php echo SITE_URL; ?>/receitas/listar.php">Ver receitas</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" enctype="multipart/form-data" class="receita-form" id="receitaForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome da Receita *</label>
                    <input type="text" id="nome" name="nome" required 
                           placeholder="Ex: Bolo de Chocolate" 
                           value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoria *</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="Sobremesa" <?php echo (($_POST['categoria'] ?? '') === 'Sobremesa') ? 'selected' : ''; ?>>Sobremesa</option>
                        <option value="Massa" <?php echo (($_POST['categoria'] ?? '') === 'Massa') ? 'selected' : ''; ?>>Massa</option>
                        <option value="Carne" <?php echo (($_POST['categoria'] ?? '') === 'Carne') ? 'selected' : ''; ?>>Carne</option>
                        <option value="Peixe" <?php echo (($_POST['categoria'] ?? '') === 'Peixe') ? 'selected' : ''; ?>>Peixe</option>
                        <option value="Ave" <?php echo (($_POST['categoria'] ?? '') === 'Ave') ? 'selected' : ''; ?>>Ave</option>
                        <option value="Vegetariano" <?php echo (($_POST['categoria'] ?? '') === 'Vegetariano') ? 'selected' : ''; ?>>Vegetariano</option>
                        <option value="Salada" <?php echo (($_POST['categoria'] ?? '') === 'Salada') ? 'selected' : ''; ?>>Salada</option>
                        <option value="Sopa" <?php echo (($_POST['categoria'] ?? '') === 'Sopa') ? 'selected' : ''; ?>>Sopa</option>
                        <option value="Bebida" <?php echo (($_POST['categoria'] ?? '') === 'Bebida') ? 'selected' : ''; ?>>Bebida</option>
                        <option value="Outro" <?php echo (($_POST['categoria'] ?? '') === 'Outro') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tempo_preparo">Tempo de Preparo *</label>
                    <input type="text" id="tempo_preparo" name="tempo_preparo" required 
                           placeholder="Ex: 30 minutos" 
                           value="<?php echo htmlspecialchars($_POST['tempo_preparo'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="nivel_dificuldade">Nível de Dificuldade *</label>
                    <select id="nivel_dificuldade" name="nivel_dificuldade" required>
                        <option value="">Selecione o nível</option>
                        <option value="Fácil" <?php echo (($_POST['nivel_dificuldade'] ?? '') === 'Fácil') ? 'selected' : ''; ?>>Fácil</option>
                        <option value="Médio" <?php echo (($_POST['nivel_dificuldade'] ?? '') === 'Médio') ? 'selected' : ''; ?>>Médio</option>
                        <option value="Difícil" <?php echo (($_POST['nivel_dificuldade'] ?? '') === 'Difícil') ? 'selected' : ''; ?>>Difícil</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="ingredientes">Ingredientes *</label>
                <textarea id="ingredientes" name="ingredientes" rows="6" required 
                          placeholder="Liste os ingredientes, um por linha ou separados por vírgula..."><?php echo htmlspecialchars($_POST['ingredientes'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="modo_preparo">Modo de Preparo *</label>
                <textarea id="modo_preparo" name="modo_preparo" rows="8" required 
                          placeholder="Descreva passo a passo o modo de preparo..."><?php echo htmlspecialchars($_POST['modo_preparo'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="imagem">Imagem da Receita</label>
                <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/jpg,image/png,image/gif">
                <small>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 5MB</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cadastrar Receita</button>
                <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>
