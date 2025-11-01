-- ============================================
-- SCRIPT PARA GERENCIAR CATEGORIAS
-- ============================================

-- 1. VER TODAS AS CATEGORIAS EXISTENTES
-- Execute primeiro para ver quais categorias existem
SELECT * FROM categoria ORDER BY id;

-- ============================================
-- 2. VER CATEGORIAS COM CONTAGEM DE RECEITAS
-- ============================================
-- Para saber quantas receitas cada categoria tem antes de apagar
SELECT 
    c.id,
    c.nome as categoria,
    COUNT(r.id) as total_receitas
FROM categoria c
LEFT JOIN receita r ON c.id = r.categoria_id
GROUP BY c.id, c.nome
ORDER BY c.id;

-- ============================================
-- 3. EXCLUIR CATEGORIAS ESPECÍFICAS
-- ============================================

-- ⚠️ ATENÇÃO: Execute os comandos abaixo com CUIDADO!
-- Isso irá apagar definitivamente as categorias do banco

-- Exemplo 1: Excluir uma categoria específica por ID
-- DELETE FROM categoria WHERE id = 7;

-- Exemplo 2: Excluir uma categoria específica por nome
-- DELETE FROM categoria WHERE nome = 'Nome_da_Categoria';

-- Exemplo 3: Excluir múltiplas categorias por ID
-- DELETE FROM categoria WHERE id IN (7, 8, 9);

-- Exemplo 4: Excluir múltiplas categorias por nome
-- DELETE FROM categoria WHERE nome IN ('Categoria1', 'Categoria2');

-- ============================================
-- 4. SCRIPT SEGURO PARA EXCLUIR CATEGORIAS
-- ============================================

-- PASSO 1: Primeiro, mover as receitas dessas categorias para uma categoria padrão
-- (Substitua '1' pelo ID da categoria que você quer manter, ex: 'Diversos')

-- UPDATE receita 
-- SET categoria_id = 1 
-- WHERE categoria_id IN (7, 8, 9);  -- IDs das categorias que você quer apagar

-- PASSO 2: Depois apagar as categorias vazias
-- DELETE FROM categoria WHERE id IN (7, 8, 9);

-- ============================================
-- 5. COMANDOS PARA CASOS ESPECÍFICOS
-- ============================================

-- Se você quer apagar TODAS as receitas de uma categoria antes de apagar a categoria:
-- DELETE FROM receita WHERE categoria_id = 7;  -- Substitua 7 pelo ID da categoria
-- DELETE FROM categoria WHERE id = 7;

-- Para resetar e recriar todas as categorias do zero:
-- TRUNCATE TABLE receita;  -- ⚠️ CUIDADO: Apaga TODAS as receitas
-- TRUNCATE TABLE categoria;  -- ⚠️ CUIDADO: Apaga TODAS as categorias

-- Depois recriar as categorias básicas:
-- INSERT INTO categoria (nome) VALUES 
-- ('Doces'),
-- ('Bebidas'),
-- ('Carnes'),
-- ('Lanches'),
-- ('Massas'),
-- ('Sopas');

-- ============================================
-- 6. VERIFICAR RESULTADO APÓS EXCLUSÃO
-- ============================================

-- Execute novamente para confirmar as mudanças:
-- SELECT * FROM categoria ORDER BY id;

-- ============================================
-- INSTRUÇÕES DE USO:
-- ============================================

/*
1. Abra o phpMyAdmin ou MySQL Workbench
2. Conecte ao banco de dados 'site_receitas'
3. Execute primeiro o comando SELECT para ver as categorias
4. Identifique os IDs ou nomes das categorias que quer apagar
5. Use os comandos DELETE apropriados
6. Execute o SELECT final para confirmar

DICAS DE SEGURANÇA:
- Sempre faça backup do banco antes de excluir
- Execute SELECT primeiro para ver o que será afetado
- Use WHERE específico para evitar apagar tudo
- Considere mover receitas antes de apagar categorias
*/