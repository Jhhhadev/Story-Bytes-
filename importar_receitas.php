<?php
require_once 'config.php';
include('backend/conexao.php');

echo "<h1>🍽️ Importação de Receitas das Páginas</h1>";

// Primeiro, vamos criar/verificar as categorias
$categorias = [
    'bebidas' => 'Bebidas',
    'doces' => 'Doces', 
    'massas' => 'Massas',
    'carnes' => 'Carnes',
    'sopas' => 'Sopas',
    'lanches' => 'Lanches'
];

echo "<h2>📋 Verificando categorias...</h2>";

// Inserir/verificar categorias
foreach ($categorias as $slug => $nome) {
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

echo "<h2>🍽️ Importando receitas...</h2>";

// Definir receitas manualmente (extraídas das páginas)
$receitas = [
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
    ]
];

// Buscar ID de um usuário admin para atribuir as receitas
$sql_admin = "SELECT id FROM usuario WHERE tipo_usuario = 'admin' LIMIT 1";
$result_admin = $conn->query($sql_admin);
$admin_id = 1; // padrão

if ($result_admin && $result_admin->num_rows > 0) {
    $admin = $result_admin->fetch_assoc();
    $admin_id = $admin['id'];
}

echo "<p>👤 Usuário responsável pelas receitas: ID {$admin_id}</p>";

// Inserir receitas
$receitas_inseridas = 0;
$receitas_existentes = 0;

foreach ($receitas as $receita) {
    // Buscar ID da categoria
    $sql_cat = "SELECT id FROM categoria WHERE nome = ?";
    $stmt_cat = $conn->prepare($sql_cat);
    $stmt_cat->bind_param("s", $receita['categoria']);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    $categoria_id = $result_cat->fetch_assoc()['id'];
    $stmt_cat->close();
    
    // Verificar se receita já existe
    $sql_exists = "SELECT id FROM receita WHERE titulo = ?";
    $stmt_exists = $conn->prepare($sql_exists);
    $stmt_exists->bind_param("s", $receita['titulo']);
    $stmt_exists->execute();
    $result_exists = $stmt_exists->get_result();
    
    if ($result_exists->num_rows > 0) {
        echo "⚠️ Receita já existe: {$receita['titulo']}<br>";
        $receitas_existentes++;
        $stmt_exists->close();
        continue;
    }
    $stmt_exists->close();
    
    // Inserir receita
    $sql_receita = "INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aprovada', NOW())";
    
    $stmt_receita = $conn->prepare($sql_receita);
    $stmt_receita->bind_param("iissssss", 
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
    
    if ($stmt_receita->execute()) {
        echo "✅ Receita inserida: {$receita['titulo']}<br>";
        $receitas_inseridas++;
    } else {
        echo "❌ Erro ao inserir: {$receita['titulo']} - " . $stmt_receita->error . "<br>";
    }
    
    $stmt_receita->close();
}

echo "<hr>";
echo "<h2>📊 Resumo da Importação</h2>";
echo "<p>✅ <strong>Receitas inseridas:</strong> {$receitas_inseridas}</p>";
echo "<p>⚠️ <strong>Receitas já existentes:</strong> {$receitas_existentes}</p>";
echo "<p>📝 <strong>Total processadas:</strong> " . count($receitas) . "</p>";

echo "<hr>";
echo "<h3>🔍 Verificar resultado:</h3>";
echo "<p><a href='debug_receitas.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Ver Receitas no Banco</a></p>";
echo "<p><a href='pages/perfil.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Ir para Perfil</a></p>";

$conn->close();
?>