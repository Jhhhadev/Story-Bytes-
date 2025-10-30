-- =========================================
-- SCRIPT PARA ALTERAR CATEGORIAS DAS RECEITAS
-- Story-Bytes Database Management
-- =========================================

USE site_receitas;

-- =========================================
-- 1. VERIFICAR CATEGORIAS DISPONÍVEIS
-- =========================================
SELECT 
    id,
    nome as categoria,
    (SELECT COUNT(*) FROM receita WHERE categoria_id = categoria.id) as total_receitas
FROM categoria 
ORDER BY nome;

-- =========================================
-- 2. VERIFICAR RECEITAS SEM CATEGORIA
-- =========================================
SELECT 
    id,
    titulo,
    'SEM CATEGORIA' as categoria_atual
FROM receita 
WHERE categoria_id IS NULL 
ORDER BY titulo;

-- =========================================
-- 3. VERIFICAR TODAS AS RECEITAS COM SUAS CATEGORIAS
-- =========================================
SELECT 
    r.id,
    r.titulo,
    COALESCE(c.nome, 'SEM CATEGORIA') as categoria_atual,
    r.datacriacao
FROM receita r 
LEFT JOIN categoria c ON r.categoria_id = c.id 
ORDER BY r.titulo;

-- =========================================
-- 4. EXEMPLOS DE ALTERAÇÃO INDIVIDUAL
-- =========================================

-- Alterar uma receita específica pelo ID
-- SUBSTITUA: [ID_DA_RECEITA] pelo ID real da receita
-- SUBSTITUA: [ID_DA_CATEGORIA] pelo ID da nova categoria

-- Exemplo: Mover receita ID 1 para categoria "Doces" (ID 1)
-- UPDATE receita SET categoria_id = 1 WHERE id = 1;

-- Exemplo: Mover receita ID 5 para categoria "Carnes" (ID 2)  
-- UPDATE receita SET categoria_id = 2 WHERE id = 5;

-- =========================================
-- 5. EXEMPLOS DE ALTERAÇÃO EM LOTE
-- =========================================

-- Mover TODAS as receitas sem categoria para "Lanches" (ID 4)
-- UPDATE receita SET categoria_id = 4 WHERE categoria_id IS NULL;

-- Mover todas as receitas de "Doces" para "Sobremesas" (se existir)
-- UPDATE receita SET categoria_id = [ID_SOBREMESAS] WHERE categoria_id = 1;

-- Mover receitas específicas por título (contém palavra)
-- UPDATE receita SET categoria_id = 3 WHERE titulo LIKE '%bebida%' OR titulo LIKE '%suco%';

-- =========================================
-- 6. COMANDOS DE VERIFICAÇÃO APÓS ALTERAÇÕES
-- =========================================

-- Contar receitas por categoria após alterações
SELECT 
    c.nome as categoria,
    COUNT(r.id) as quantidade_receitas
FROM categoria c 
LEFT JOIN receita r ON c.id = r.categoria_id 
GROUP BY c.id, c.nome 
ORDER BY quantidade_receitas DESC, c.nome;

-- Verificar se ainda há receitas sem categoria
SELECT COUNT(*) as receitas_sem_categoria 
FROM receita 
WHERE categoria_id IS NULL;

-- =========================================
-- 7. SCRIPTS ESPECÍFICOS POR CATEGORIA
-- =========================================

-- DOCES (categoria_id = 1)
-- Buscar possíveis receitas de doces por palavras-chave
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%doce%' OR titulo LIKE '%bolo%' OR titulo LIKE '%torta%' 
       OR titulo LIKE '%pudim%' OR titulo LIKE '%brigadeiro%' OR titulo LIKE '%mousse%')
AND categoria_id != 1;

-- CARNES (categoria_id = 2)  
-- Buscar possíveis receitas de carnes por palavras-chave
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%carne%' OR titulo LIKE '%frango%' OR titulo LIKE '%peixe%' 
       OR titulo LIKE '%bife%' OR titulo LIKE '%porco%' OR titulo LIKE '%churrasco%')
AND categoria_id != 2;

-- BEBIDAS (categoria_id = 3)
-- Buscar possíveis receitas de bebidas por palavras-chave  
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%bebida%' OR titulo LIKE '%suco%' OR titulo LIKE '%água%' 
       OR titulo LIKE '%chá%' OR titulo LIKE '%café%' OR titulo LIKE '%vitamina%')
AND categoria_id != 3;

-- LANCHES (categoria_id = 4)
-- Buscar possíveis receitas de lanches por palavras-chave
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%lanche%' OR titulo LIKE '%sanduíche%' OR titulo LIKE '%pão%' 
       OR titulo LIKE '%salgado%' OR titulo LIKE '%hambúrguer%' OR titulo LIKE '%wrap%')
AND categoria_id != 4;

-- MASSAS (categoria_id = 5)
-- Buscar possíveis receitas de massas por palavras-chave
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%massa%' OR titulo LIKE '%macarrão%' OR titulo LIKE '%espaguete%' 
       OR titulo LIKE '%lasanha%' OR titulo LIKE '%nhoque%' OR titulo LIKE '%pizza%')
AND categoria_id != 5;

-- SOPAS (categoria_id = 6)
-- Buscar possíveis receitas de sopas por palavras-chave
SELECT id, titulo, categoria_id 
FROM receita 
WHERE (titulo LIKE '%sopa%' OR titulo LIKE '%caldo%' OR titulo LIKE '%canja%' 
       OR titulo LIKE '%consomê%' OR titulo LIKE '%creme%')
AND categoria_id != 6;

-- =========================================
-- 8. COMANDOS DE CORREÇÃO AUTOMÁTICA
-- =========================================

-- DESCOMENTE E EXECUTE COM CUIDADO!
-- Estas alterações são baseadas em palavras-chave nos títulos

-- Mover automaticamente receitas com palavras-chave de DOCES
-- UPDATE receita SET categoria_id = 1 
-- WHERE (titulo LIKE '%doce%' OR titulo LIKE '%bolo%' OR titulo LIKE '%torta%' 
--        OR titulo LIKE '%pudim%' OR titulo LIKE '%brigadeiro%' OR titulo LIKE '%mousse%')
-- AND categoria_id IS NULL;

-- Mover automaticamente receitas com palavras-chave de CARNES  
-- UPDATE receita SET categoria_id = 2 
-- WHERE (titulo LIKE '%carne%' OR titulo LIKE '%frango%' OR titulo LIKE '%peixe%' 
--        OR titulo LIKE '%bife%' OR titulo LIKE '%porco%' OR titulo LIKE '%churrasco%')
-- AND categoria_id IS NULL;

-- Mover automaticamente receitas com palavras-chave de BEBIDAS
-- UPDATE receita SET categoria_id = 3 
-- WHERE (titulo LIKE '%bebida%' OR titulo LIKE '%suco%' OR titulo LIKE '%água%' 
--        OR titulo LIKE '%chá%' OR titulo LIKE '%café%' OR titulo LIKE '%vitamina%')
-- AND categoria_id IS NULL;

-- Mover automaticamente receitas com palavras-chave de LANCHES
-- UPDATE receita SET categoria_id = 4 
-- WHERE (titulo LIKE '%lanche%' OR titulo LIKE '%sanduíche%' OR titulo LIKE '%pão%' 
--        OR titulo LIKE '%salgado%' OR titulo LIKE '%hambúrguer%' OR titulo LIKE '%wrap%')
-- AND categoria_id IS NULL;

-- Mover automaticamente receitas com palavras-chave de MASSAS
-- UPDATE receita SET categoria_id = 5 
-- WHERE (titulo LIKE '%massa%' OR titulo LIKE '%macarrão%' OR titulo LIKE '%espaguete%' 
--        OR titulo LIKE '%lasanha%' OR titulo LIKE '%nhoque%' OR titulo LIKE '%pizza%')
-- AND categoria_id IS NULL;

-- Mover automaticamente receitas com palavras-chave de SOPAS
-- UPDATE receita SET categoria_id = 6 
-- WHERE (titulo LIKE '%sopa%' OR titulo LIKE '%caldo%' OR titulo LIKE '%canja%' 
--        OR titulo LIKE '%consomê%' OR titulo LIKE '%creme%')
-- AND categoria_id IS NULL;

-- =========================================
-- 9. COMANDOS DE BACKUP E RESTAURAÇÃO
-- =========================================

-- Criar tabela de backup antes de grandes alterações
-- CREATE TABLE receita_backup AS SELECT * FROM receita;

-- Restaurar da tabela de backup (se necessário)
-- DELETE FROM receita;
-- INSERT INTO receita SELECT * FROM receita_backup;

-- =========================================
-- 10. RELATÓRIO FINAL
-- =========================================

-- Relatório completo de distribuição de receitas
SELECT 
    'TOTAL GERAL' as categoria,
    COUNT(*) as quantidade,
    '100%' as percentual
FROM receita

UNION ALL

SELECT 
    COALESCE(c.nome, 'SEM CATEGORIA') as categoria,
    COUNT(r.id) as quantidade,
    CONCAT(ROUND((COUNT(r.id) * 100.0 / (SELECT COUNT(*) FROM receita)), 1), '%') as percentual
FROM categoria c 
LEFT JOIN receita r ON c.id = r.categoria_id 
GROUP BY c.id, c.nome

UNION ALL

SELECT 
    'SEM CATEGORIA' as categoria,
    COUNT(*) as quantidade,
    CONCAT(ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM receita)), 1), '%') as percentual
FROM receita 
WHERE categoria_id IS NULL

ORDER BY quantidade DESC;

-- =========================================
-- FIM DO SCRIPT
-- =========================================

/*
INSTRUÇÕES DE USO:

1. SEMPRE faça backup antes de executar alterações em lote
2. Execute primeiro os comandos de verificação (seções 1-3)
3. Use os exemplos das seções 4-5 como modelo
4. Para alterações automáticas, descomente os comandos da seção 8
5. Execute o relatório final (seção 10) para verificar os resultados

ATENÇÃO: 
- Substitua [ID_DA_RECEITA] e [ID_DA_CATEGORIA] pelos valores reais
- Teste sempre com uma receita antes de alterações em lote
- Mantenha backups atualizados
*/