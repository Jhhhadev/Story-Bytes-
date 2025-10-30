-- =========================================
-- SCRIPT PARA INSERIR RECEITA: BOLO DE LIMÃO
-- Story-Bytes Database
-- =========================================

USE site_receitas;

-- =========================================
-- 1. VERIFICAR ESTRUTURA ANTES DA INSERÇÃO
-- =========================================

-- Verificar se as categorias existem
SELECT id, nome FROM categoria WHERE nome = 'Doces';

-- Verificar se há usuários cadastrados (usaremos o primeiro disponível)
SELECT id, nome FROM usuario LIMIT 5;

-- =========================================
-- 2. INSERIR A RECEITA DE BOLO DE LIMÃO
-- =========================================

INSERT INTO receita (
    usuario_id,
    categoria_id, 
    titulo,
    descricao,
    ingredientes,
    modoprep,
    rendimento,
    tempo_preparo,
    imagem,
    status_aprovacao,
    datacriacao
) VALUES (
    1, -- usuario_id (ajuste conforme necessário)
    1, -- categoria_id (1 = Doces)
    'Bolo de Limão Cremoso',
    'Um delicioso bolo de limão macio e suculento, perfeito para acompanhar um café da tarde. Com sabor cítrico marcante e textura fofa, este bolo é uma sobremesa irresistível que agrada toda a família.',
    '**Massa:**
• 3 ovos
• 1 xícara de açúcar
• 1/2 xícara de óleo
• 1 xícara de leite
• 2 xícaras de farinha de trigo
• 1 colher de sopa de fermento em pó
• Raspas de 2 limões
• 1/4 xícara de suco de limão

**Calda:**
• 1/2 xícara de açúcar
• 1/3 xícara de suco de limão
• 2 colheres de sopa de água

**Cobertura (opcional):**
• 1 xícara de açúcar de confeiteiro
• 3 colheres de sopa de suco de limão
• Raspas de limão para decorar',
    '**Preparo da Massa:**
1. Pré-aqueça o forno a 180°C e unte uma forma de bolo com manteiga e farinha.

2. Em uma tigela grande, bata os ovos com o açúcar até obter um creme claro e fofo (cerca de 5 minutos).

3. Adicione o óleo e o leite, misturando bem.

4. Acrescente as raspas de limão e o suco de limão, incorporando delicadamente.

5. Em outra tigela, peneire a farinha com o fermento.

6. Adicione os ingredientes secos à mistura líquida, mexendo suavemente até formar uma massa homogênea.

7. Despeje a massa na forma preparada e leve ao forno por 35-40 minutos, ou até que um palito inserido no centro saia limpo.

**Preparo da Calda:**
8. Enquanto o bolo assa, prepare a calda misturando o açúcar, suco de limão e água em uma panela pequena.

9. Leve ao fogo baixo até o açúcar dissolver completamente. Reserve.

**Finalização:**
10. Assim que o bolo sair do forno, ainda quente, faça furos com um palito por toda a superfície.

11. Despeje a calda sobre o bolo quente, permitindo que seja absorvida.

12. Deixe esfriar completamente antes de desenformar.

**Cobertura (opcional):**
13. Misture o açúcar de confeiteiro com o suco de limão até formar uma pasta lisa.

14. Cubra o bolo com a mistura e decore com raspas de limão.

15. Sirva em temperatura ambiente.',
    '8-10 fatias',
    '1 hora e 30 minutos',
    'bolo-limao.jpg', -- Nome da imagem (deve ser colocada na pasta img/receitas/)
    'aprovada',
    NOW()
);

-- =========================================
-- 3. VERIFICAR SE A INSERÇÃO FOI BEM-SUCEDIDA
-- =========================================

-- Buscar a receita recém-inserida
SELECT 
    r.id,
    r.titulo,
    r.descricao,
    c.nome as categoria,
    u.nome as autor,
    r.rendimento,
    r.tempo_preparo,
    r.datacriacao
FROM receita r
LEFT JOIN categoria c ON r.categoria_id = c.id
LEFT JOIN usuario u ON r.usuario_id = u.id
WHERE r.titulo = 'Bolo de Limão Cremoso';

-- =========================================
-- 4. ATUALIZAR CONTADORES (OPCIONAL)
-- =========================================

-- Verificar quantas receitas de doces temos agora
SELECT 
    c.nome as categoria,
    COUNT(r.id) as total_receitas
FROM categoria c
LEFT JOIN receita r ON c.id = r.categoria_id
WHERE c.nome = 'Doces'
GROUP BY c.id, c.nome;

-- =========================================
-- 5. SCRIPT ALTERNATIVO COM VALIDAÇÕES
-- =========================================

-- Caso queira inserir com validações mais robustas:

DELIMITER //

CREATE PROCEDURE InserirBoloLimao()
BEGIN
    DECLARE usuario_exists INT DEFAULT 0;
    DECLARE categoria_exists INT DEFAULT 0;
    DECLARE receita_exists INT DEFAULT 0;
    
    -- Verificar se existe usuário
    SELECT COUNT(*) INTO usuario_exists FROM usuario LIMIT 1;
    
    -- Verificar se categoria Doces existe
    SELECT COUNT(*) INTO categoria_exists FROM categoria WHERE nome = 'Doces';
    
    -- Verificar se a receita já existe
    SELECT COUNT(*) INTO receita_exists FROM receita WHERE titulo = 'Bolo de Limão Cremoso';
    
    -- Inserir apenas se todas as condições forem atendidas
    IF usuario_exists > 0 AND categoria_exists > 0 AND receita_exists = 0 THEN
        INSERT INTO receita (
            usuario_id,
            categoria_id, 
            titulo,
            descricao,
            ingredientes,
            modoprep,
            rendimento,
            tempo_preparo,
            imagem,
            status_aprovacao,
            datacriacao
        ) VALUES (
            1,
            (SELECT id FROM categoria WHERE nome = 'Doces' LIMIT 1),
            'Bolo de Limão Cremoso',
            'Um delicioso bolo de limão macio e suculento, perfeito para acompanhar um café da tarde. Com sabor cítrico marcante e textura fofa, este bolo é uma sobremesa irresistível que agrada toda a família.',
            '**Massa:**\n• 3 ovos\n• 1 xícara de açúcar\n• 1/2 xícara de óleo\n• 1 xícara de leite\n• 2 xícaras de farinha de trigo\n• 1 colher de sopa de fermento em pó\n• Raspas de 2 limões\n• 1/4 xícara de suco de limão\n\n**Calda:**\n• 1/2 xícara de açúcar\n• 1/3 xícara de suco de limão\n• 2 colheres de sopa de água\n\n**Cobertura (opcional):**\n• 1 xícara de açúcar de confeiteiro\n• 3 colheres de sopa de suco de limão\n• Raspas de limão para decorar',
            '**Preparo da Massa:**\n1. Pré-aqueça o forno a 180°C e unte uma forma de bolo com manteiga e farinha.\n\n2. Em uma tigela grande, bata os ovos com o açúcar até obter um creme claro e fofo (cerca de 5 minutos).\n\n3. Adicione o óleo e o leite, misturando bem.\n\n4. Acrescente as raspas de limão e o suco de limão, incorporando delicadamente.\n\n5. Em outra tigela, peneire a farinha com o fermento.\n\n6. Adicione os ingredientes secos à mistura líquida, mexendo suavemente até formar uma massa homogênea.\n\n7. Despeje a massa na forma preparada e leve ao forno por 35-40 minutos, ou até que um palito inserido no centro saia limpo.\n\n**Preparo da Calda:**\n8. Enquanto o bolo assa, prepare a calda misturando o açúcar, suco de limão e água em uma panela pequena.\n\n9. Leve ao fogo baixo até o açúcar dissolver completamente. Reserve.\n\n**Finalização:**\n10. Assim que o bolo sair do forno, ainda quente, faça furos com um palito por toda a superfície.\n\n11. Despeje a calda sobre o bolo quente, permitindo que seja absorvida.\n\n12. Deixe esfriar completamente antes de desenformar.\n\n**Cobertura (opcional):**\n13. Misture o açúcar de confeiteiro com o suco de limão até formar uma pasta lisa.\n\n14. Cubra o bolo com a mistura e decore com raspas de limão.\n\n15. Sirva em temperatura ambiente.',
            '8-10 fatias',
            '1 hora e 30 minutos',
            'bolo-limao.jpg',
            'aprovada',
            NOW()
        );
        
        SELECT 'Receita de Bolo de Limão inserida com sucesso!' as resultado;
    ELSE
        SELECT 'Erro: Verifique se existem usuários, categoria Doces, ou se a receita já não existe.' as resultado;
    END IF;
END //

DELIMITER ;

-- Para executar o procedimento:
-- CALL InserirBoloLimao();

-- Para remover o procedimento após uso:
-- DROP PROCEDURE InserirBoloLimao;

-- =========================================
-- 6. COMANDOS DE VERIFICAÇÃO FINAL
-- =========================================

-- Listar todas as receitas de doces
SELECT 
    r.id,
    r.titulo,
    r.rendimento,
    r.tempo_preparo,
    r.datacriacao
FROM receita r
JOIN categoria c ON r.categoria_id = c.id
WHERE c.nome = 'Doces'
ORDER BY r.datacriacao DESC;

-- Estatísticas gerais após inserção
SELECT 
    (SELECT COUNT(*) FROM receita) as total_receitas,
    (SELECT COUNT(*) FROM receita WHERE categoria_id = 1) as receitas_doces,
    (SELECT COUNT(*) FROM receita WHERE titulo LIKE '%limão%') as receitas_limao;

-- =========================================
-- FIM DO SCRIPT
-- =========================================

/*
INSTRUÇÕES DE USO:

1. Execute este script no MySQL Workbench ou phpMyAdmin
2. Ajuste o usuario_id conforme necessário (linha 28)
3. Certifique-se de que a categoria "Doces" existe (ID = 1)
4. Opcionalmente, adicione a imagem 'bolo-limao.jpg' na pasta img/receitas/
5. Execute as verificações finais para confirmar a inserção

OBSERVAÇÕES:
- A receita será criada com status 'aprovada'
- Todos os campos obrigatórios estão preenchidos
- A receita incluí ingredientes detalhados e modo de preparo completo
- Tempo e rendimento estão especificados
*/