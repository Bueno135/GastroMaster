-- Banco de Dados do GastroMaster
-- Sistema de Cadastro e Gerenciamento de Receitas Gastronômicas

CREATE DATABASE IF NOT EXISTS gastromaster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE gastromaster;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de receitas
CREATE TABLE IF NOT EXISTS receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(200) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    ingredientes TEXT NOT NULL,
    modo_preparo TEXT NOT NULL,
    tempo_preparo VARCHAR(50) NOT NULL,
    nivel_dificuldade VARCHAR(20) NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para melhor performance
CREATE INDEX idx_usuario_id ON receitas(usuario_id);
CREATE INDEX idx_categoria ON receitas(categoria);

