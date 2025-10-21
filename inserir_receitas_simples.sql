-- =====================================================
-- SCRIPT SIMPLES PARA INSERIR RECEITAS NO MySQL
-- =====================================================
-- Execute este arquivo no MySQL Workbench ou terminal
-- Comando: mysql -u root -h localhost site_receitas < inserir_receitas_simples.sql

USE site_receitas;

-- Criar categorias se não existirem
INSERT IGNORE INTO categoria (nome) VALUES ('Bebidas'), ('Doces'), ('Massas'), ('Carnes'), ('Sopas'), ('Lanches');

-- Limpar receitas duplicadas se necessário (opcional)
-- DELETE FROM receita WHERE titulo IN ('Suco de Laranja', 'Bolo') AND id NOT IN (SELECT * FROM (SELECT MIN(id) FROM receita WHERE titulo IN ('Suco de Laranja', 'Bolo') GROUP BY titulo) as x);

-- INSERIR AS 12 RECEITAS DAS PÁGINAS
-- BEBIDAS (ID categoria = 1)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 1, 'Chá Gelado de Hibisco com Limão', 'Uma bebida refrescante e saudável, perfeita para os dias quentes.', '2 colheres (sopa) de flores de hibisco secas\n1 litro de água fervente\nSuco de 1 limão\nMel ou adoçante a gosto\nGelo a gosto', '1. Coloque o hibisco na água fervente e deixe em infusão por 10 minutos.\n2. Coe, espere esfriar e adicione o suco de limão.\n3. Adoce se desejar e leve à geladeira.\n4. Sirva bem gelado com bastante gelo.', '1 jarra (1 litro)', '15 minutos', 'cha-hibisco.jpg', 'aprovada', NOW()),
(1, 1, 'Água Saborizada com Frutas Cítricas', 'Uma alternativa saudável aos refrigerantes, refrescante e cheia de vitaminas.', '1,5 litro de água gelada\n1 laranja em rodelas\n1 limão siciliano em rodelas\n1 limão taiti em rodelas\nFolhas de hortelã fresca\nGelo a gosto', '1. Coloque a água em uma jarra grande.\n2. Adicione as rodelas de frutas cítricas e folhas de hortelã.\n3. Complete com gelo.\n4. Deixe descansar por 15 minutos na geladeira antes de servir.', '1 jarra (1,5 litro)', '10 minutos', 'agua-saborcitrico.jpg', 'aprovada', NOW());

-- DOCES (ID categoria = 2)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 2, 'Bolo de Cenoura com Cobertura de Chocolate', 'O clássico bolo de cenoura brasileiro, fofinho e úmido.', 'MASSA:\n3 cenouras médias descascadas\n3 ovos\n1 xícara de óleo\n2 xícaras de açúcar\n2½ xícaras de farinha de trigo\n1 colher (sopa) de fermento\n\nCOBERTURA:\n4 colheres (sopa) de cacau\n½ xícara de leite\n2 colheres (sopa) de manteiga\n1 xícara de açúcar', '1. Bata no liquidificador as cenouras, ovos e óleo.\n2. Misture com açúcar e farinha peneirada.\n3. Incorpore o fermento e asse a 180°C por 35-45 min.\n4. COBERTURA: leve tudo ao fogo baixo até engrossar.', '10 porções', '60 minutos', 'bolo-cenoura.jpeg', 'aprovada', NOW()),
(1, 2, 'Pudim de Leite Condensado', 'O pudim mais amado do Brasil! Cremoso e com calda caramelizada.', 'PUDIM:\n1 lata de leite condensado\n1 lata de leite\n3 ovos\n\nCALDA:\n1 xícara de açúcar\n½ xícara de água', '1. Derreta o açúcar até dourar, adicione água.\n2. Despeje na forma.\n3. Bata ingredientes do pudim no liquidificador.\n4. Despeje sobre a calda.\n5. Asse em banho-maria por 50-60 min.', '8 porções', '80 minutos', 'pudim.jpeg', 'aprovada', NOW());

-- MASSAS (ID categoria = 3)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 3, 'Espaguete à Bolonhesa', 'Clássico espaguete com molho rico e saboroso.', '500g de espaguete\n400g de carne moída\n1 lata de molho de tomate\n2 tomates picados\n1 cebola picada\n2 dentes de alho\nSal, pimenta e azeite\nQueijo parmesão ralado', '1. Cozinhe o espaguete al dente.\n2. Refogue cebola e alho no azeite.\n3. Adicione carne moída e doure.\n4. Junte tomates e molho, cozinhe 15 min.\n5. Misture com a massa e finalize com queijo.', '5 porções', '40 minutos', 'spagueti-bolognese.jpeg', 'aprovada', NOW()),
(1, 3, 'Macarrão à Carbonara', 'Receita italiana clássica e cremosa com bacon e ovos.', '400g de espaguete\n200g de bacon em cubos\n3 gemas de ovo\n1 ovo inteiro\n1 xícara de parmesão ralado\nPimenta-do-reino preta\nSal a gosto', '1. Cozinhe a massa al dente.\n2. Frite o bacon até dourar.\n3. Misture gemas, ovo e queijo.\n4. Misture massa quente com bacon.\n5. Retire do fogo e adicione ovos mexendo rápido.\n6. Tempere com pimenta.', '4 porções', '25 minutos', 'spagueti-carbonara.jpeg', 'aprovada', NOW());

-- CARNES (ID categoria = 4)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 4, 'Bife Acebolado', 'Prato simples da culinária brasileira com bifes e cebolas douradas.', '4 bifes de alcatra\n3 cebolas grandes fatiadas\n2 dentes de alho picados\nAzeite e óleo\nSal e pimenta-do-reino\nSalsinha picada', '1. Tempere os bifes com sal e pimenta.\n2. Doure os bifes no óleo, reserve.\n3. Refogue alho e cebola até dourar.\n4. Volte os bifes com as cebolas.\n5. Cozinhe mais alguns minutos e finalize com salsinha.', '4 porções', '30 minutos', 'bife-acebolado.jpeg', 'aprovada', NOW()),
(1, 4, 'Frango ao Molho de Mostarda', 'Peitos de frango em cremoso molho de mostarda.', '4 peitos de frango\n2 colheres (sopa) de mostarda dijon\n1 xícara de creme de leite\n1 cebola picada\n2 dentes de alho\nAzeite, sal e pimenta\nErvas finas', '1. Tempere e doure o frango no azeite.\n2. Refogue cebola e alho.\n3. Adicione mostarda e creme de leite.\n4. Volte o frango ao molho até ficar macio.\n5. Finalize com ervas.', '4 porções', '35 minutos', 'frango-mostarda.jpeg', 'aprovada', NOW());

-- SOPAS (ID categoria = 5)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 5, 'Sopa Cremosa de Mandioquinha', 'Sopa cremosa e nutritiva, perfeita para dias frios.', '500g de mandioquinha picada\n1 cebola picada\n2 dentes de alho\n1 litro de caldo de legumes\n200ml de creme de leite\nAzeite, sal e pimenta\nSalsinha picada', '1. Refogue cebola e alho no azeite.\n2. Adicione mandioquinha e refogue 5 min.\n3. Cubra com caldo e cozinhe até amolecer.\n4. Bata no liquidificador até cremosa.\n5. Volte à panela, adicione creme e tempere.', '6 porções', '40 minutos', 'sopa-mandioquinha.jpeg', 'aprovada', NOW()),
(1, 5, 'Sopa de Legumes Caseira', 'Sopa nutritiva e reconfortante com vegetais frescos.', '2 cenouras picadas\n2 batatas picadas\n1 abobrinha picada\n1 cebola picada\n2 dentes de alho\n1 litro de caldo de legumes\nVagem cortada\nMilho verde\nAzeite, sal e pimenta\nErvas frescas', '1. Refogue cebola e alho.\n2. Adicione cenoura e batata, refogue 5 min.\n3. Cubra com caldo e cozinhe 15 min.\n4. Adicione abobrinha, vagem e milho.\n5. Cozinhe até amolecer.\n6. Tempere e finalize com ervas.', '6 porções', '35 minutos', 'sopa-legumes.jpeg', 'aprovada', NOW());

-- LANCHES (ID categoria = 6)
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) VALUES 
(1, 6, 'Sanduíche Natural de Frango', 'Lanche saudável com frango desfiado e vegetais frescos.', '8 fatias de pão integral\n2 peitos de frango cozidos e desfiados\n2 tomates fatiados\nFolhas de alface\n1 cenoura ralada\nMaionese light\nRequeijão light\nSal e pimenta', '1. Tempere o frango desfiado.\n2. Passe requeijão no pão.\n3. Monte com frango, alface, tomate e cenoura.\n4. Passe maionese na outra fatia.\n5. Corte na diagonal e sirva.', '4 sanduíches', '15 minutos', 'sanduiche-frango.jpeg', 'aprovada', NOW()),
(1, 6, 'Wrap Integral de Atum com Salada', 'Wrap nutritivo e prático, ideal para trabalho ou escola.', '4 tortillas integrais\n2 latas de atum em água\n1 tomate picado\n1 pepino picado\nFolhas de rúcula\n1 cenoura ralada\nIogurte natural\nMostarda\nSal e pimenta', '1. Escorra e tempere o atum.\n2. Misture iogurte com mostarda.\n3. Espalhe o molho na tortilla.\n4. Adicione atum, vegetais e rúcula.\n5. Enrole bem apertado e corte ao meio.', '4 wraps', '12 minutos', 'wrap-atum.jpeg', 'aprovada', NOW());

-- Mostrar resultado
SELECT 'Importação concluída!' as STATUS;
SELECT COUNT(*) as 'Total de Receitas no Banco' FROM receita;
SELECT c.nome as Categoria, COUNT(r.id) as Quantidade FROM categoria c LEFT JOIN receita r ON c.id = r.categoria_id GROUP BY c.nome ORDER BY c.nome;