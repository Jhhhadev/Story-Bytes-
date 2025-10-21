-- Script para atualizar banco de dados com novas funcionalidades
-- Execute este script no MySQL para adicionar as colunas necessárias

USE site_receitas;

-- Adicionar colunas na tabela receita se não existirem
ALTER TABLE receita 
ADD COLUMN IF NOT EXISTS usuario_id INT,
ADD COLUMN IF NOT EXISTS categoria_id INT,
ADD COLUMN IF NOT EXISTS ingredientes TEXT,
ADD COLUMN IF NOT EXISTS tempo_preparo VARCHAR(100),
ADD COLUMN IF NOT EXISTS imagem VARCHAR(255),
ADD COLUMN IF NOT EXISTS status_aprovacao ENUM('pendente', 'aprovada', 'rejeitada') DEFAULT 'pendente',
ADD FOREIGN KEY IF NOT EXISTS (usuario_id) REFERENCES usuario(id),
ADD FOREIGN KEY IF NOT EXISTS (categoria_id) REFERENCES categoria(id);

-- Inserir categorias padrão se não existirem
INSERT IGNORE INTO categoria (id, nome) VALUES 
(1, 'Doces'),
(2, 'Massas'), 
(3, 'Carnes'),
(4, 'Sopas'),
(5, 'Lanches'),
(6, 'Bebidas'),
(7, 'Sobremesas'),
(8, 'Saladas'),
(9, 'Pães'),
(10, 'Molhos');

-- Adicionar coluna email na tabela usuario se não existir (para exibir no perfil)
ALTER TABLE usuario 
ADD COLUMN IF NOT EXISTS email_backup VARCHAR(100);

-- Criar tabela para controle de aprovações (histórico)
CREATE TABLE IF NOT EXISTS aprovacao_receita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receita_id INT NOT NULL,
    admin_id INT NOT NULL,
    status_anterior ENUM('pendente', 'aprovada', 'rejeitada'),
    status_novo ENUM('pendente', 'aprovada', 'rejeitada'),
    comentario_admin TEXT,
    data_aprovacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receita_id) REFERENCES receita(id),
    FOREIGN KEY (admin_id) REFERENCES usuario(id)
);

-- Criar diretório para imagens (comando do sistema)
-- mkdir -p ../img/receitas/