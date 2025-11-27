<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/ReceitaRepository.php';
require_once __DIR__ . '/../services/ImageUploader.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$erro = '';
$sucesso = '';

$repositorio = new ReceitaRepository();
$receita = $id ? $repositorio->findByIdAndUser($id, $_SESSION['user_id']) : null;

if (!$receita && $id) {
    $erro = 'Receita não encontrada.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $receita) {
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
        $imagem = $receita['imagem'];
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploader = new ImageUploader();
            $upload = $uploader->upload($_FILES['imagem'], $receita['imagem']);
            if (!$upload['success']) {
                $erro = $upload['message'];
            } else {
                $imagem = $upload['filename'];
            }
        }
        
        if (empty($erro)) {
            if ($repositorio->update($id, $_SESSION['user_id'], [
                'nome' => $nome,
                'categoria' => $categoria,
                'ingredientes' => $ingredientes,
                'modo_preparo' => $modo_preparo,
                'tempo_preparo' => $tempo_preparo,
                'nivel_dificuldade' => $nivel_dificuldade,
                'imagem' => $imagem
            ])) {
                $sucesso = 'Receita atualizada com sucesso!';
                $receita = array_merge($receita, [
                    'nome' => $nome,
                    'categoria' => $categoria,
                    'ingredientes' => $ingredientes,
                    'modo_preparo' => $modo_preparo,
                    'tempo_preparo' => $tempo_preparo,
                    'nivel_dificuldade' => $nivel_dificuldade,
                    'imagem' => $imagem
                ]);
            } else {
                $erro = 'Erro ao atualizar receita. Tente novamente.';
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
    <title>Editar Receita - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <div class="container">
        <?php if ($erro && !$receita): ?>
            <div class="alert alert-error"><?php echo $erro; ?></div>
            <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Voltar</a>
        <?php else: ?>
            <div class="page-header">
                <h1>Editar Receita</h1>
                <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Voltar</a>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-error"><?php echo $erro; ?></div>
            <?php endif; ?>
            
            <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <?php echo $sucesso; ?>
                    <a href="<?php echo SITE_URL; ?>/receitas/ver.php?id=<?php echo $id; ?>">Ver receita</a>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data" class="receita-form" id="receitaForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome da Receita *</label>
                        <input type="text" id="nome" name="nome" required 
                               placeholder="Ex: Bolo de Chocolate" 
                               value="<?php echo htmlspecialchars($receita['nome'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria">Categoria *</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="Sobremesa" <?php echo (($receita['categoria'] ?? '') === 'Sobremesa') ? 'selected' : ''; ?>>Sobremesa</option>
                            <option value="Massa" <?php echo (($receita['categoria'] ?? '') === 'Massa') ? 'selected' : ''; ?>>Massa</option>
                            <option value="Carne" <?php echo (($receita['categoria'] ?? '') === 'Carne') ? 'selected' : ''; ?>>Carne</option>
                            <option value="Peixe" <?php echo (($receita['categoria'] ?? '') === 'Peixe') ? 'selected' : ''; ?>>Peixe</option>
                            <option value="Ave" <?php echo (($receita['categoria'] ?? '') === 'Ave') ? 'selected' : ''; ?>>Ave</option>
                            <option value="Vegetariano" <?php echo (($receita['categoria'] ?? '') === 'Vegetariano') ? 'selected' : ''; ?>>Vegetariano</option>
                            <option value="Salada" <?php echo (($receita['categoria'] ?? '') === 'Salada') ? 'selected' : ''; ?>>Salada</option>
                            <option value="Sopa" <?php echo (($receita['categoria'] ?? '') === 'Sopa') ? 'selected' : ''; ?>>Sopa</option>
                            <option value="Bebida" <?php echo (($receita['categoria'] ?? '') === 'Bebida') ? 'selected' : ''; ?>>Bebida</option>
                            <option value="Outro" <?php echo (($receita['categoria'] ?? '') === 'Outro') ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="tempo_preparo">Tempo de Preparo *</label>
                        <input type="text" id="tempo_preparo" name="tempo_preparo" required 
                               placeholder="Ex: 30 minutos" 
                               value="<?php echo htmlspecialchars($receita['tempo_preparo'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="nivel_dificuldade">Nível de Dificuldade *</label>
                        <select id="nivel_dificuldade" name="nivel_dificuldade" required>
                            <option value="">Selecione o nível</option>
                            <option value="Fácil" <?php echo (($receita['nivel_dificuldade'] ?? '') === 'Fácil') ? 'selected' : ''; ?>>Fácil</option>
                            <option value="Médio" <?php echo (($receita['nivel_dificuldade'] ?? '') === 'Médio') ? 'selected' : ''; ?>>Médio</option>
                            <option value="Difícil" <?php echo (($receita['nivel_dificuldade'] ?? '') === 'Difícil') ? 'selected' : ''; ?>>Difícil</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="ingredientes">Ingredientes *</label>
                    <textarea id="ingredientes" name="ingredientes" rows="6" required 
                              placeholder="Liste os ingredientes, um por linha ou separados por vírgula..."><?php echo htmlspecialchars($receita['ingredientes'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="modo_preparo">Modo de Preparo *</label>
                    <textarea id="modo_preparo" name="modo_preparo" rows="8" required 
                              placeholder="Descreva passo a passo o modo de preparo..."><?php echo htmlspecialchars($receita['modo_preparo'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="imagem">Imagem da Receita</label>
                    <?php if ($receita['imagem']): ?>
                        <div class="current-image">
                            <img src="<?php echo UPLOAD_URL . $receita['imagem']; ?>" 
                                 alt="Imagem atual" style="max-width: 200px; margin-bottom: 10px;">
                            <p><small>Imagem atual</small></p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/jpg,image/png,image/gif">
                    <small>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 5MB. Deixe em branco para manter a imagem atual.</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="<?php echo SITE_URL; ?>/receitas/listar.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>
