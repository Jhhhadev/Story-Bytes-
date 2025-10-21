-- Script para verificar e corrigir estrutura do banco de dados
-- Execute este script para garantir que tudo está funcionando corretamente

USE site_receitas;

-- Verificar se a tabela usuario tem todas as colunas necessárias
DESCRIBE usuario;

-- Se a coluna dataCadastro for do tipo DATE, ela deve estar funcionando
-- Se houver problemas, você pode ajustar com:
-- ALTER TABLE usuario MODIFY COLUMN dataCadastro DATE NOT NULL;

-- Verificar se há registros na tabela
SELECT COUNT(*) as total_usuarios FROM usuario;

-- Listar todos os usuários (para verificar estrutura)
SELECT id, nome, email, dataCadastro, tipo_usuario FROM usuario LIMIT 5;

-- Testar se as constraints estão funcionando
-- (Este comando deve falhar se o email for duplicado)
-- INSERT INTO usuario (nome, email, senha, dataCadastro, tipo_usuario) 
-- VALUES ('Teste', 'email_existente@teste.com', 'senha123', CURDATE(), 'comum');

-- Verificar índices na tabela
SHOW INDEX FROM usuario;