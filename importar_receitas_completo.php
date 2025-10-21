<?php
require_once 'config.php';
include('backend/conexao.php');

echo "<h1>🍽️ Importação Completa de Receitas das Páginas</h1>";

// Receitas completas extraídas das páginas
$receitas_completas = [
    // BEBIDAS
    [
        'categoria' => 'Bebidas',
        'titulo' => 'Chá Gelado de Hibisco com Limão',
        'descricao' => 'Uma bebida refrescante e saudável, perfeita para os dias quentes. O hibisco é rico em antioxidantes e o limão adiciona um toque cítrico delicioso.',
        'ingredientes' => "2 colheres (sopa) de flores de hibisco secas (ou sachês de chá de hibisco)\n1 litro de água fervente\nSuco de 1 limão\nMel ou adoçante a gosto\nGelo a gosto",
        'modoprep' => "1. Coloque o hibisco na água fervente e deixe em infusão por 10 minutos.\n2. Coe, espere esfriar e adicione o suco de limão.\n3. Adoce se desejar e leve à geladeira.\n4. Sirva bem gelado com bastante gelo.",
        'rendimento' => '1 jarra (1 litro)',
        'tempo_preparo' => '15 minutos',
        'imagem' => 'cha-hibisco.jpg'
    ],
    [
        'categoria' => 'Bebidas',
        'titulo' => 'Água Saborizada com Frutas Cítricas',
        'descricao' => 'Uma alternativa saudável aos refrigerantes, esta água saborizada é refrescante e cheia de vitaminas naturais das frutas cítricas.',
        'ingredientes' => "1,5 litro de água gelada\n1 laranja em rodelas\n1 limão siciliano em rodelas\n1 limão taiti em rodelas\nFolhas de hortelã fresca\nGelo a gosto",
        'modoprep' => "1. Coloque a água em uma jarra grande.\n2. Adicione as rodelas de frutas cítricas e folhas de hortelã.\n3. Complete com gelo.\n4. Deixe descansar por 15 minutos na geladeira antes de servir.",
        'rendimento' => '1 jarra (1,5 litro)',
        'tempo_preparo' => '10 minutos',
        'imagem' => 'agua-saborcitrico.jpg'
    ],
    
    // DOCES
    [
        'categoria' => 'Doces',
        'titulo' => 'Bolo de Cenoura com Cobertura de Chocolate',
        'descricao' => 'O clássico bolo de cenoura brasileiro, fofinho e úmido, com uma deliciosa cobertura de chocolate que derrete na boca.',
        'ingredientes' => "MASSA:\n3 cenouras médias descascadas e picadas\n3 ovos\n1 xícara de óleo\n2 xícaras de açúcar\n2 e 1/2 xícaras de farinha de trigo\n1 colher (sopa) de fermento químico\n\nCOBERTURA:\n4 colheres (sopa) de cacau em pó\n1/2 xícara de leite\n2 colheres (sopa) de manteiga\n1 xícara de açúcar",
        'modoprep' => "1. Bata no liquidificador as cenouras, ovos e óleo até ficar homogêneo.\n2. Em uma tigela, misture o creme com o açúcar e a farinha peneirada.\n3. Incorpore o fermento delicadamente e leve ao forno pré-aquecido (180 °C) por 35–45 min.\n4. COBERTURA: leve tudo ao fogo baixo, mexendo, até engrossar. Espalhe sobre o bolo morno.",
        'rendimento' => '10 porções',
        'tempo_preparo' => '60 minutos',
        'imagem' => 'bolo-cenoura.jpeg'
    ],
    [
        'categoria' => 'Doces',
        'titulo' => 'Pudim de Leite Condensado',
        'descricao' => 'O pudim mais amado do Brasil! Cremoso, doce na medida certa e com aquela calda de açúcar caramelizada irresistível.',
        'ingredientes' => "PUDIM:\n1 lata de leite condensado\n1 lata de leite (use a lata do leite condensado como medida)\n3 ovos\n\nCALDA:\n1 xícara de açúcar\n1/2 xícara de água",
        'modoprep' => "1. Faça a calda: derreta o açúcar em uma panela até dourar, adicione a água com cuidado.\n2. Despeje a calda na forma e espalhe.\n3. Bata todos os ingredientes do pudim no liquidificador.\n4. Despeje na forma por cima da calda.\n5. Asse em banho-maria por 50-60 minutos.\n6. Deixe esfriar e desenforme.",
        'rendimento' => '8 porções',
        'tempo_preparo' => '80 minutos',
        'imagem' => 'pudim.jpeg'
    ],
    
    // MASSAS
    [
        'categoria' => 'Massas',
        'titulo' => 'Espaguete à Bolonhesa',
        'descricao' => 'O clássico espaguete à bolonhesa com um molho rico e saboroso, perfeito para um almoço em família.',
        'ingredientes' => "500 g de espaguete\n400 g de carne moída\n1 lata de molho de tomate\n2 tomates picados\n1 cebola picada\n2 dentes de alho picados\nSal, pimenta e azeite a gosto\nQueijo parmesão ralado",
        'modoprep' => "1. Cozinhe o espaguete até ficar al dente. Escorra e reserve.\n2. Refogue cebola e alho no azeite.\n3. Adicione a carne moída e deixe dourar.\n4. Junte tomates, molho e temperos. Cozinhe por 15 min.\n5. Misture com a massa e finalize com queijo ralado.",
        'rendimento' => '5 porções',
        'tempo_preparo' => '40 minutos',
        'imagem' => 'spagueti-bolognese.jpeg'
    ],
    [
        'categoria' => 'Massas',
        'titulo' => 'Macarrão à Carbonara',
        'descricao' => 'A receita italiana clássica e cremosa, com bacon, ovos e queijo parmesão. Uma explosão de sabores.',
        'ingredientes' => "400 g de espaguete ou fettuccine\n200 g de bacon em cubos\n3 gemas de ovo\n1 ovo inteiro\n1 xícara de queijo parmesão ralado\nPimenta-do-reino preta moída\nSal a gosto",
        'modoprep' => "1. Cozinhe a massa al dente e reserve um pouco da água do cozimento.\n2. Frite o bacon até ficar dourado.\n3. Misture gemas, ovo e queijo em uma tigela.\n4. Misture a massa quente com o bacon.\n5. Retire do fogo e adicione a mistura de ovos, mexendo rapidamente.\n6. Tempere com pimenta e sirva imediatamente.",
        'rendimento' => '4 porções',
        'tempo_preparo' => '25 minutos',
        'imagem' => 'spagueti-carbonara.jpeg'
    ],
    
    // CARNES
    [
        'categoria' => 'Carnes',
        'titulo' => 'Bife Acebolado',
        'descricao' => 'Um prato simples e saboroso da culinária brasileira, com bifes macios e cebolas douradas.',
        'ingredientes' => "4 bifes de alcatra ou contra-filé\n3 cebolas grandes fatiadas\n2 dentes de alho picados\nAzeite e óleo para refogar\nSal e pimenta-do-reino a gosto\nSalsinha picada para finalizar",
        'modoprep' => "1. Tempere os bifes com sal e pimenta.\n2. Doure os bifes em uma frigideira com óleo, reserve.\n3. Na mesma frigideira, refogue alho e cebola até dourar.\n4. Volte os bifes à frigideira com as cebolas.\n5. Cozinhe por mais alguns minutos e finalize com salsinha.",
        'rendimento' => '4 porções',
        'tempo_preparo' => '30 minutos',
        'imagem' => 'bife-acebolado.jpeg'
    ],
    [
        'categoria' => 'Carnes',
        'titulo' => 'Frango ao Molho de Mostarda',
        'descricao' => 'Peitos de frango suculentos em um cremoso molho de mostarda, perfeito para um jantar especial.',
        'ingredientes' => "4 peitos de frango\n2 colheres (sopa) de mostarda dijon\n1 xícara de creme de leite\n1 cebola picada\n2 dentes de alho picados\nAzeite, sal e pimenta a gosto\nErvas finas ou tomilho",
        'modoprep' => "1. Tempere o frango com sal e pimenta.\n2. Doure o frango no azeite, reserve.\n3. Refogue cebola e alho na mesma panela.\n4. Adicione mostarda e creme de leite, mexendo bem.\n5. Volte o frango ao molho e cozinhe até ficar macio.\n6. Finalize com ervas e sirva.",
        'rendimento' => '4 porções',
        'tempo_preparo' => '35 minutos',
        'imagem' => 'frango-mostarda.jpeg'
    ],
    
    // SOPAS
    [
        'categoria' => 'Sopas',
        'titulo' => 'Sopa Cremosa de Mandioquinha',
        'descricao' => 'Uma sopa cremosa e nutritiva, perfeita para os dias mais frios. A mandioquinha traz um sabor único e delicado.',
        'ingredientes' => "500 g de mandioquinha descascada e picada\n1 cebola picada\n2 dentes de alho picados\n1 litro de caldo de legumes\n200 ml de creme de leite\nAzeite, sal e pimenta a gosto\nSalsinha picada para finalizar",
        'modoprep' => "1. Refogue cebola e alho no azeite.\n2. Adicione a mandioquinha e refogue por 5 minutos.\n3. Cubra com o caldo e cozinhe até amolecer.\n4. Bata no liquidificador até ficar cremosa.\n5. Volte à panela, adicione o creme de leite e tempere.\n6. Finalize com salsinha e sirva quente.",
        'rendimento' => '6 porções',
        'tempo_preparo' => '40 minutos',
        'imagem' => 'sopa-mandioquinha.jpeg'
    ],
    [
        'categoria' => 'Sopas',
        'titulo' => 'Sopa de Legumes Caseira',
        'descricao' => 'Uma sopa nutritiva e reconfortante, cheia de vegetais frescos e sabor caseiro.',
        'ingredientes' => "2 cenouras picadas\n2 batatas picadas\n1 abobrinha picada\n1 cebola picada\n2 dentes de alho picados\n1 litro de caldo de legumes\nVagem cortada\nMilho verde\nAzeite, sal e pimenta a gosto\nErvas frescas",
        'modoprep' => "1. Refogue cebola e alho no azeite.\n2. Adicione cenoura e batata, refogue por 5 minutos.\n3. Cubra com caldo e cozinhe por 15 minutos.\n4. Adicione abobrinha, vagem e milho.\n5. Cozinhe até todos os legumes ficarem macios.\n6. Tempere e finalize com ervas frescas.",
        'rendimento' => '6 porções',
        'tempo_preparo' => '35 minutos',
        'imagem' => 'sopa-legumes.jpeg'
    ],
    
    // LANCHES
    [
        'categoria' => 'Lanches',
        'titulo' => 'Sanduíche Natural de Frango',
        'descricao' => 'Um lanche saudável e saboroso, perfeito para qualquer hora do dia, com frango desfiado e vegetais frescos.',
        'ingredientes' => "8 fatias de pão de forma integral\n2 peitos de frango cozidos e desfiados\n2 tomates fatiados\nFolhas de alface\n1 cenoura ralada\nMaionese light\nRequeijão light\nSal e pimenta a gosto",
        'modoprep' => "1. Tempere o frango desfiado com sal e pimenta.\n2. Passe requeijão em uma fatia de pão.\n3. Monte o sanduíche com frango, alface, tomate e cenoura.\n4. Passe maionese na outra fatia e feche.\n5. Corte na diagonal e sirva.",
        'rendimento' => '4 sanduíches',
        'tempo_preparo' => '15 minutos',
        'imagem' => 'sanduiche-frango.jpeg'
    ],
    [
        'categoria' => 'Lanches',
        'titulo' => 'Wrap Integral de Atum com Salada',
        'descricao' => 'Um wrap nutritivo e prático, ideal para levar para o trabalho ou escola. Leve e cheio de sabor.',
        'ingredientes' => "4 tortillas integrais\n2 latas de atum em água\n1 tomate picado\n1 pepino picado\nFolhas de rúcula\n1 cenoura ralada\nIogurte natural\nMostarda\nSal e pimenta a gosto",
        'modoprep' => "1. Escorra bem o atum e tempere com sal e pimenta.\n2. Misture iogurte com um pouco de mostarda.\n3. Espalhe o molho na tortilla.\n4. Adicione atum, vegetais e rúcula.\n5. Enrole bem apertado e corte ao meio.\n6. Sirva imediatamente.",
        'rendimento' => '4 wraps',
        'tempo_preparo' => '12 minutos',
        'imagem' => 'wrap-atum.jpeg'
    ]
];

// Verificar/criar categorias
$categorias = ['Bebidas', 'Doces', 'Massas', 'Carnes', 'Sopas', 'Lanches'];

echo "<h2>📋 Verificando categorias...</h2>";
foreach ($categorias as $nome) {
    $sql_check = "SELECT id FROM categoria WHERE nome = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $nome);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows == 0) {
        $sql_insert = "INSERT INTO categoria (nome) VALUES (?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("s", $nome);
        $stmt_insert->execute();
        echo "✅ Categoria criada: {$nome}<br>";
        $stmt_insert->close();
    } else {
        echo "ℹ️ Categoria já existe: {$nome}<br>";
    }
    $stmt_check->close();
}

// Buscar admin ID
$sql_admin = "SELECT id FROM usuario WHERE tipo_usuario = 'admin' LIMIT 1";
$result_admin = $conn->query($sql_admin);
$admin_id = 1;

if ($result_admin && $result_admin->num_rows > 0) {
    $admin = $result_admin->fetch_assoc();
    $admin_id = $admin['id'];
}

echo "<p>👤 Atribuindo receitas ao usuário ID: {$admin_id}</p>";

echo "<h2>🍽️ Importando receitas...</h2>";

$inseridas = 0;
$existentes = 0;
$erros = 0;

foreach ($receitas_completas as $receita) {
    // Buscar categoria ID
    $sql_cat = "SELECT id FROM categoria WHERE nome = ?";
    $stmt_cat = $conn->prepare($sql_cat);
    $stmt_cat->bind_param("s", $receita['categoria']);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    $categoria_id = $result_cat->fetch_assoc()['id'];
    $stmt_cat->close();
    
    // Verificar se existe
    $sql_exists = "SELECT id FROM receita WHERE titulo = ?";
    $stmt_exists = $conn->prepare($sql_exists);
    $stmt_exists->bind_param("s", $receita['titulo']);
    $stmt_exists->execute();
    
    if ($stmt_exists->get_result()->num_rows > 0) {
        echo "⚠️ Já existe: {$receita['titulo']}<br>";
        $existentes++;
        $stmt_exists->close();
        continue;
    }
    $stmt_exists->close();
    
    // Inserir receita
    $sql_insert = "INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aprovada', NOW())";
    
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iissssss",
        $admin_id,
        $categoria_id,
        $receita['titulo'],
        $receita['descricao'],
        $receita['ingredientes'],
        $receita['modoprep'],
        $receita['rendimento'],
        $receita['tempo_preparo'],
        $receita['imagem']
    );
    
    if ($stmt_insert->execute()) {
        echo "✅ Inserida: {$receita['titulo']}<br>";
        $inseridas++;
    } else {
        echo "❌ Erro: {$receita['titulo']} - " . $stmt_insert->error . "<br>";
        $erros++;
    }
    
    $stmt_insert->close();
}

echo "<hr>";
echo "<h2>📊 Resumo Final</h2>";
echo "<div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0;'>";

echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; text-align: center;'>";
echo "<h3 style='color: #155724; margin: 0;'>✅ Inseridas</h3>";
echo "<p style='font-size: 2rem; margin: 10px 0; color: #155724;'>{$inseridas}</p>";
echo "</div>";

echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; text-align: center;'>";
echo "<h3 style='color: #856404; margin: 0;'>⚠️ Existentes</h3>";
echo "<p style='font-size: 2rem; margin: 10px 0; color: #856404;'>{$existentes}</p>";
echo "</div>";

echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; text-align: center;'>";
echo "<h3 style='color: #721c24; margin: 0;'>❌ Erros</h3>";
echo "<p style='font-size: 2rem; margin: 10px 0; color: #721c24;'>{$erros}</p>";
echo "</div>";

echo "</div>";

echo "<p><strong>Total processadas:</strong> " . count($receitas_completas) . " receitas</p>";

if ($inseridas > 0) {
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3 style='color: #0c5460;'>🎉 Sucesso!</h3>";
    echo "<p>As receitas foram importadas com sucesso para o banco de dados!</p>";
    echo "<p>Agora elas aparecerão nas páginas do site e podem ser gerenciadas pelo sistema.</p>";
    echo "</div>";
}

echo "<h3>🔗 Próximos passos:</h3>";
echo "<p><a href='debug_receitas.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📋 Ver Receitas no Banco</a>";
echo "<a href='pages/perfil.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>👤 Ir para Perfil</a></p>";

$conn->close();
?>