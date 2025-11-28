<?php

require_once __DIR__ . '/../config/config.php';

class ImageUploader
{
    // Faz upload de imagem com validações de tipo e tamanho
    public function upload($arquivo, $imagemAtual = null)
    {
        if (!isset($arquivo['error']) || is_array($arquivo['error'])) {
            return ['success' => false, 'message' => 'Parâmetros de upload inválidos.'];
        }

        if ($arquivo['error'] === UPLOAD_ERR_NO_FILE) {
            if ($imagemAtual) {
                return ['success' => true, 'message' => '', 'filename' => $imagemAtual];
            }
            return ['success' => false, 'message' => 'Por favor, selecione uma imagem.'];
        }

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erro ao fazer upload do arquivo.'];
        }

        if ($arquivo['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'Arquivo muito grande. Tamanho máximo: 5MB.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($arquivo['tmp_name']);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use JPG, PNG ou GIF.'];
        }

        if (!file_exists(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $extension = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $filename = uniqid('receita_', true) . '.' . $extension;
        $filepath = UPLOAD_DIR . $filename;

        if (!move_uploaded_file($arquivo['tmp_name'], $filepath)) {
            return ['success' => false, 'message' => 'Erro ao salvar o arquivo.'];
        }

        if ($imagemAtual && file_exists(UPLOAD_DIR . $imagemAtual)) {
            unlink(UPLOAD_DIR . $imagemAtual);
        }

        return ['success' => true, 'message' => 'Upload realizado com sucesso.', 'filename' => $filename];
    }
}

