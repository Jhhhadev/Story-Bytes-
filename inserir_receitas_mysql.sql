-- Script SQL para inserir receitas das páginas no banco de dados
-- Execute este script diretamente no MySQL

-- Primeiro, garantir que as categorias existem
INSERT IGNORE INTO categoria (nome) VALUES 
('Bebidas'),
('Doces'),
('Massas'),
('Carnes'),
('Sopas'),
('Lanches');

-- Inserir as 12 receitas das páginas
-- BEBIDAS
INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) 
VALUES 
(1, (SELECT id FROM categoria WHERE nome = 'Bebidas'), 
'Chá Gelado de Hibisco com Limão', 
'Uma bebida refrescante e saudável, perfeita para os dias quentes. O hibisco é rico em antioxidantes e o limão adiciona um toque cítrico delicioso.',
'2 colheres (sopa) de flores de hibisco secas (ou sachês de chá de hibisco)
1 litro de água fervente
Suco de 1 limão
Mel ou adoçante a gosto
Gelo a gosto',
'1. Coloque o hibisco na água fervente e deixe em infusão por 10 minutos.
2. Coe, espere esfriar e adicione o suco de limão.
3. Adoce se desejar e leve à geladeira.
4. Sirva bem gelado com bastante gelo.',
'1 jarra (1 litro)',
'15 minutos',
'cha-hibisco.jpg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Bebidas'), 
'Água Saborizada com Frutas Cítricas', 
'Uma alternativa saudável aos refrigerantes, esta água saborizada é refrescante e cheia de vitaminas naturais das frutas cítricas.',
'1,5 litro de água gelada
1 laranja em rodelas
1 limão siciliano em rodelas
1 limão taiti em rodelas
Folhas de hortelã fresca
Gelo a gosto',
'1. Coloque a água em uma jarra grande.
2. Adicione as rodelas de frutas cítricas e folhas de hortelã.
3. Complete com gelo.
4. Deixe descansar por 15 minutos na geladeira antes de servir.',
'1 jarra (1,5 litro)',
'10 minutos',
'agua-saborcitrico.jpg',
'aprovada',
NOW()),

-- DOCES
(1, (SELECT id FROM categoria WHERE nome = 'Doces'), 
'Bolo de Cenoura com Cobertura de Chocolate', 
'O clássico bolo de cenoura brasileiro, fofinho e úmido, com uma deliciosa cobertura de chocolate que derrete na boca.',
'MASSA:
3 cenouras médias descascadas e picadas
3 ovos
1 xícara de óleo
2 xícaras de açúcar
2 e 1/2 xícaras de farinha de trigo
1 colher (sopa) de fermento químico

COBERTURA:
4 colheres (sopa) de cacau em pó
1/2 xícara de leite
2 colheres (sopa) de manteiga
1 xícara de açúcar',
'1. Bata no liquidificador as cenouras, ovos e óleo até ficar homogêneo.
2. Em uma tigela, misture o creme com o açúcar e a farinha peneirada.
3. Incorpore o fermento delicadamente e leve ao forno pré-aquecido (180 °C) por 35–45 min.
4. COBERTURA: leve tudo ao fogo baixo, mexendo, até engrossar. Espalhe sobre o bolo morno.',
'10 porções',
'60 minutos',
'bolo-cenoura.jpeg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Doces'), 
'Pudim de Leite Condensado', 
'O pudim mais amado do Brasil! Cremoso, doce na medida certa e com aquela calda de açúcar caramelizada irresistível.',
'PUDIM:
1 lata de leite condensado
1 lata de leite (use a lata do leite condensado como medida)
3 ovos

CALDA:
1 xícara de açúcar
1/2 xícara de água',
'1. Faça a calda: derreta o açúcar em uma panela até dourar, adicione a água com cuidado.
2. Despeje a calda na forma e espalhe.
3. Bata todos os ingredientes do pudim no liquidificador.
4. Despeje na forma por cima da calda.
5. Asse em banho-maria por 50-60 minutos.
6. Deixe esfriar e desenforme.',
'8 porções',
'80 minutos',
'pudim.jpeg',
'aprovada',
NOW()),

-- MASSAS
(1, (SELECT id FROM categoria WHERE nome = 'Massas'), 
'Espaguete à Bolonhesa', 
'O clássico espaguete à bolonhesa com um molho rico e saboroso, perfeito para um almoço em família.',
'500 g de espaguete
400 g de carne moída
1 lata de molho de tomate
2 tomates picados
1 cebola picada
2 dentes de alho picados
Sal, pimenta e azeite a gosto
Queijo parmesão ralado',
'1. Cozinhe o espaguete até ficar al dente. Escorra e reserve.
2. Refogue cebola e alho no azeite.
3. Adicione a carne moída e deixe dourar.
4. Junte tomates, molho e temperos. Cozinhe por 15 min.
5. Misture com a massa e finalize com queijo ralado.',
'5 porções',
'40 minutos',
'spagueti-bolognese.jpeg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Massas'), 
'Macarrão à Carbonara', 
'A receita italiana clássica e cremosa, com bacon, ovos e queijo parmesão. Uma explosão de sabores.',
'400 g de espaguete ou fettuccine
200 g de bacon em cubos
3 gemas de ovo
1 ovo inteiro
1 xícara de queijo parmesão ralado
Pimenta-do-reino preta moída
Sal a gosto',
'1. Cozinhe a massa al dente e reserve um pouco da água do cozimento.
2. Frite o bacon até ficar dourado.
3. Misture gemas, ovo e queijo em uma tigela.
4. Misture a massa quente com o bacon.
5. Retire do fogo e adicione a mistura de ovos, mexendo rapidamente.
6. Tempere com pimenta e sirva imediatamente.',
'4 porções',
'25 minutos',
'spagueti-carbonara.jpeg',
'aprovada',
NOW()),

-- CARNES
(1, (SELECT id FROM categoria WHERE nome = 'Carnes'), 
'Bife Acebolado', 
'Um prato simples e saboroso da culinária brasileira, com bifes macios e cebolas douradas.',
'4 bifes de alcatra ou contra-filé
3 cebolas grandes fatiadas
2 dentes de alho picados
Azeite e óleo para refogar
Sal e pimenta-do-reino a gosto
Salsinha picada para finalizar',
'1. Tempere os bifes com sal e pimenta.
2. Doure os bifes em uma frigideira com óleo, reserve.
3. Na mesma frigideira, refogue alho e cebola até dourar.
4. Volte os bifes à frigideira com as cebolas.
5. Cozinhe por mais alguns minutos e finalize com salsinha.',
'4 porções',
'30 minutos',
'bife-acebolado.jpeg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Carnes'), 
'Frango ao Molho de Mostarda', 
'Peitos de frango suculentos em um cremoso molho de mostarda, perfeito para um jantar especial.',
'4 peitos de frango
2 colheres (sopa) de mostarda dijon
1 xícara de creme de leite
1 cebola picada
2 dentes de alho picados
Azeite, sal e pimenta a gosto
Ervas finas ou tomilho',
'1. Tempere o frango com sal e pimenta.
2. Doure o frango no azeite, reserve.
3. Refogue cebola e alho na mesma panela.
4. Adicione mostarda e creme de leite, mexendo bem.
5. Volte o frango ao molho e cozinhe até ficar macio.
6. Finalize com ervas e sirva.',
'4 porções',
'35 minutos',
'frango-mostarda.jpeg',
'aprovada',
NOW()),

-- SOPAS
(1, (SELECT id FROM categoria WHERE nome = 'Sopas'), 
'Sopa Cremosa de Mandioquinha', 
'Uma sopa cremosa e nutritiva, perfeita para os dias mais frios. A mandioquinha traz um sabor único e delicado.',
'500 g de mandioquinha descascada e picada
1 cebola picada
2 dentes de alho picados
1 litro de caldo de legumes
200 ml de creme de leite
Azeite, sal e pimenta a gosto
Salsinha picada para finalizar',
'1. Refogue cebola e alho no azeite.
2. Adicione a mandioquinha e refogue por 5 minutos.
3. Cubra com o caldo e cozinhe até amolecer.
4. Bata no liquidificador até ficar cremosa.
5. Volte à panela, adicione o creme de leite e tempere.
6. Finalize com salsinha e sirva quente.',
'6 porções',
'40 minutos',
'sopa-mandioquinha.jpeg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Sopas'), 
'Sopa de Legumes Caseira', 
'Uma sopa nutritiva e reconfortante, cheia de vegetais frescos e sabor caseiro.',
'2 cenouras picadas
2 batatas picadas
1 abobrinha picada
1 cebola picada
2 dentes de alho picados
1 litro de caldo de legumes
Vagem cortada
Milho verde
Azeite, sal e pimenta a gosto
Ervas frescas',
'1. Refogue cebola e alho no azeite.
2. Adicione cenoura e batata, refogue por 5 minutos.
3. Cubra com caldo e cozinhe por 15 minutos.
4. Adicione abobrinha, vagem e milho.
5. Cozinhe até todos os legumes ficarem macios.
6. Tempere e finalize com ervas frescas.',
'6 porções',
'35 minutos',
'sopa-legumes.jpeg',
'aprovada',
NOW()),

-- LANCHES
(1, (SELECT id FROM categoria WHERE nome = 'Lanches'), 
'Sanduíche Natural de Frango', 
'Um lanche saudável e saboroso, perfeito para qualquer hora do dia, com frango desfiado e vegetais frescos.',
'8 fatias de pão de forma integral
2 peitos de frango cozidos e desfiados
2 tomates fatiados
Folhas de alface
1 cenoura ralada
Maionese light
Requeijão light
Sal e pimenta a gosto',
'1. Tempere o frango desfiado com sal e pimenta.
2. Passe requeijão em uma fatia de pão.
3. Monte o sanduíche com frango, alface, tomate e cenoura.
4. Passe maionese na outra fatia e feche.
5. Corte na diagonal e sirva.',
'4 sanduíches',
'15 minutos',
'sanduiche-frango.jpeg',
'aprovada',
NOW()),

(1, (SELECT id FROM categoria WHERE nome = 'Lanches'), 
'Wrap Integral de Atum com Salada', 
'Um wrap nutritivo e prático, ideal para levar para o trabalho ou escola. Leve e cheio de sabor.',
'4 tortillas integrais
2 latas de atum em água
1 tomate picado
1 pepino picado
Folhas de rúcula
1 cenoura ralada
Iogurte natural
Mostarda
Sal e pimenta a gosto',
'1. Escorra bem o atum e tempere com sal e pimenta.
2. Misture iogurte com um pouco de mostarda.
3. Espalhe o molho na tortilla.
4. Adicione atum, vegetais e rúcula.
5. Enrole bem apertado e corte ao meio.
6. Sirva imediatamente.',
'4 wraps',
'12 minutos',
'wrap-atum.jpeg',
'aprovada',
NOW());

-- Verificar o resultado
SELECT 'RESUMO DA IMPORTAÇÃO' as STATUS;
SELECT COUNT(*) as 'Total de Receitas' FROM receita;
SELECT c.nome as 'Categoria', COUNT(r.id) as 'Quantidade' 
FROM categoria c 
LEFT JOIN receita r ON c.id = r.categoria_id 
GROUP BY c.id, c.nome 
ORDER BY c.nome;