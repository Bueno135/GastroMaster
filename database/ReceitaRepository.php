<?php

require_once __DIR__ . '/../config/database.php';

class ReceitaRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    // Lista todas as receitas do usuário
    public function findAllByUser($usuarioId)
    {
        if (!$this->pdo) {
            return [];
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM receitas WHERE usuario_id = ? ORDER BY data_cadastro DESC");
            $stmt->execute([$usuarioId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar receitas: " . $e->getMessage());
            return [];
        }
    }

    // Busca receita específica por ID e usuário
    public function findByIdAndUser($id, $usuarioId)
    {
        if (!$this->pdo) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM receitas WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuarioId]);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar receita: " . $e->getMessage());
            return null;
        }
    }

    // Cadastra nova receita
    public function create($dados)
    {
        if (!$this->pdo) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO receitas 
                (usuario_id, nome, categoria, ingredientes, modo_preparo, tempo_preparo, nivel_dificuldade, imagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $dados['usuario_id'],
                $dados['nome'],
                $dados['categoria'],
                $dados['ingredientes'],
                $dados['modo_preparo'],
                $dados['tempo_preparo'],
                $dados['nivel_dificuldade'],
                $dados['imagem'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar receita: " . $e->getMessage());
            return false;
        }
    }

    // Atualiza receita existente
    public function update($id, $usuarioId, $dados)
    {
        if (!$this->pdo) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE receitas 
                SET nome = ?, categoria = ?, ingredientes = ?, modo_preparo = ?, 
                    tempo_preparo = ?, nivel_dificuldade = ?, imagem = ?
                WHERE id = ? AND usuario_id = ?
            ");
            return $stmt->execute([
                $dados['nome'],
                $dados['categoria'],
                $dados['ingredientes'],
                $dados['modo_preparo'],
                $dados['tempo_preparo'],
                $dados['nivel_dificuldade'],
                $dados['imagem'] ?? null,
                $id,
                $usuarioId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar receita: " . $e->getMessage());
            return false;
        }
    }

    // Exclui receita e retorna nome da imagem
    public function delete($id, $usuarioId)
    {
        if (!$this->pdo) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT imagem FROM receitas WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuarioId]);
            $receita = $stmt->fetch();

            if (!$receita) {
                return null;
            }

            $stmt = $this->pdo->prepare("DELETE FROM receitas WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $usuarioId]);

            return $receita['imagem'];
        } catch (PDOException $e) {
            error_log("Erro ao excluir receita: " . $e->getMessage());
            return false;
        }
    }
}

