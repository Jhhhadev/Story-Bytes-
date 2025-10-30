<!DOCTYPE html>
<html>
<head>
    <title>Teste Específico - Página de Categoria</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .receita-debug { margin: 20px 0; padding: 15px; border: 2px solid #ccc; background: #f9f9f9; }
        img { max-width: 200px; border: 2px solid blue; }
        .erro { color: red; font-weight: bold; }
        .sucesso { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🍰 Teste Debug - Página de Doces</h1>

    <?php
    include('../backend/conexao.php');
    
    echo "<h2>Query SQL sendo executada:</h2>";
    $sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
            FROM receita r 
            LEFT JOIN categoria c ON r.categoria_id = c.id 
            LEFT JOIN usuario u ON r.usuario_id = u.id 
            WHERE c.nome = 'Doces' 
            ORDER BY r.datacriacao DESC";
    echo "<pre>" . htmlspecialchars($sql) . "</pre>";
    
    $receitas = $conn->query($sql);
    $total_receitas = $receitas ? $receitas->num_rows : 0;
    
    echo "<h2>Resultados encontrados: $total_receitas</h2>";
    
    if ($receitas && $receitas->num_rows > 0) {
        while($receita = $receitas->fetch_assoc()) {
            echo "<div class='receita-debug'>";
            echo "<h3>Receita ID: " . $receita['id'] . " - " . htmlspecialchars($receita['titulo']) . "</h3>";
            echo "<p><strong>Campo imagem no banco:</strong> " . ($receita['imagem'] ? htmlspecialchars($receita['imagem']) : 'NULL/VAZIO') . "</p>";
            
            if ($receita['imagem']) {
                $caminho_relativo = "../img/receitas/" . $receita['imagem'];
                $caminho_absoluto = "img/receitas/" . $receita['imagem'];
                
                echo "<p><strong>Caminho relativo:</strong> " . htmlspecialchars($caminho_relativo) . "</p>";
                echo "<p><strong>Arquivo existe (relativo)?</strong> " . (file_exists($caminho_relativo) ? "<span class='sucesso'>✅ SIM</span>" : "<span class='erro'>❌ NÃO</span>") . "</p>";
                
                echo "<p><strong>Caminho absoluto:</strong> " . htmlspecialchars($caminho_absoluto) . "</p>";
                echo "<p><strong>Arquivo existe (absoluto)?</strong> " . (file_exists($caminho_absoluto) ? "<span class='sucesso'>✅ SIM</span>" : "<span class='erro'>❌ NÃO</span>") . "</p>";
                
                echo "<h4>Teste de Exibição:</h4>";
                echo "<p>Caminho relativo (../img/receitas/):</p>";
                echo "<img src='" . $caminho_relativo . "' alt='Teste relativo' onerror='this.style.border=\"2px solid red\"; this.alt=\"ERRO: \" + this.src'>";
                
                echo "<p>Caminho absoluto (img/receitas/):</p>";
                echo "<img src='" . $caminho_absoluto . "' alt='Teste absoluto' onerror='this.style.border=\"2px solid red\"; this.alt=\"ERRO: \" + this.src'>";
                
                // Simulação do código da página real
                echo "<h4>Simulação do código da página real:</h4>";
                if ($receita['imagem'] && file_exists("../img/receitas/" . $receita['imagem'])) {
                    echo "<p class='sucesso'>✅ Condição file_exists() passou - imagem deveria aparecer</p>";
                    echo "<img src='../img/receitas/" . htmlspecialchars($receita['imagem']) . "' alt='" . htmlspecialchars($receita['titulo']) . "' style='border: 2px solid green;'>";
                } else {
                    echo "<p class='erro'>❌ Condição file_exists() falhou - usando imagem padrão</p>";
                    echo "<img src='../img/doces.jpg' alt='Imagem padrão' style='border: 2px solid orange;'>";
                }
            } else {
                echo "<p class='erro'>❌ Receita não tem imagem definida no banco</p>";
            }
            
            echo "</div>";
        }
    } else {
        echo "<p class='erro'>❌ Nenhuma receita encontrada na categoria Doces</p>";
    }
    
    $conn->close();
    ?>
</body>
</html>