<!DOCTYPE html>
<html>
<head>
    <title>Debug das Imagens</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: #f9f9f9; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .erro { color: red; font-weight: bold; }
        .sucesso { color: green; font-weight: bold; }
        img { max-width: 100px; height: 60px; object-fit: cover; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Completo das Imagens</h1>

    <div class="debug-section">
        <h2>1. Verificação do Banco de Dados</h2>
        <?php
        include('backend/conexao.php');
        
        // Verificar se a tabela receita tem o campo imagem
        echo "<h3>Estrutura da tabela receita:</h3>";
        $result = $conn->query("DESCRIBE receita");
        if ($result) {
            echo "<table><tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Default</th></tr>";
            $tem_campo_imagem = false;
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . $row['Null'] . "</td><td>" . $row['Default'] . "</td></tr>";
                if ($row['Field'] === 'imagem') {
                    $tem_campo_imagem = true;
                }
            }
            echo "</table>";
            
            if ($tem_campo_imagem) {
                echo "<p class='sucesso'>✅ Campo 'imagem' existe na tabela</p>";
            } else {
                echo "<p class='erro'>❌ Campo 'imagem' NÃO existe na tabela</p>";
            }
        } else {
            echo "<p class='erro'>❌ Erro ao verificar estrutura da tabela</p>";
        }
        ?>
    </div>

    <div class="debug-section">
        <h2>2. Receitas com Imagens no Banco</h2>
        <?php
        $sql = "SELECT id, titulo, imagem, datacriacao FROM receita WHERE imagem IS NOT NULL AND imagem != '' ORDER BY datacriacao DESC LIMIT 10";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            echo "<p class='sucesso'>✅ Encontradas " . $result->num_rows . " receitas com imagens no banco</p>";
            echo "<table><tr><th>ID</th><th>Título</th><th>Nome da Imagem</th><th>Arquivo Existe?</th><th>Preview</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                $caminho_imagem = "img/receitas/" . $row['imagem'];
                $arquivo_existe = file_exists($caminho_imagem);
                
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['imagem']) . "</td>";
                echo "<td>" . ($arquivo_existe ? "<span class='sucesso'>✅ SIM</span>" : "<span class='erro'>❌ NÃO</span>") . "</td>";
                echo "<td>";
                if ($arquivo_existe) {
                    echo "<img src='" . $caminho_imagem . "' alt='Preview'>";
                } else {
                    echo "<span class='erro'>Arquivo não encontrado</span>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='erro'>❌ Nenhuma receita com imagem encontrada no banco</p>";
        }
        ?>
    </div>

    <div class="debug-section">
        <h2>3. Verificação de Arquivos na Pasta</h2>
        <?php
        $pasta_receitas = "img/receitas/";
        echo "<h3>Conteúdo da pasta $pasta_receitas:</h3>";
        
        if (is_dir($pasta_receitas)) {
            $arquivos = scandir($pasta_receitas);
            $arquivos = array_diff($arquivos, array('.', '..'));
            
            if (count($arquivos) > 0) {
                echo "<p class='sucesso'>✅ Pasta existe com " . count($arquivos) . " arquivo(s)</p>";
                echo "<ul>";
                foreach ($arquivos as $arquivo) {
                    $tamanho = filesize($pasta_receitas . $arquivo);
                    echo "<li>" . $arquivo . " (" . number_format($tamanho / 1024, 2) . " KB)</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='erro'>❌ Pasta existe mas está vazia</p>";
            }
        } else {
            echo "<p class='erro'>❌ Pasta $pasta_receitas não existe</p>";
        }
        ?>
    </div>

    <div class="debug-section">
        <h2>4. Teste de Caminhos das Imagens</h2>
        <?php
        // Testar diferentes caminhos
        $caminhos_teste = [
            "img/receitas/agua-saborcitrico.jpg",
            "../img/receitas/agua-saborcitrico.jpg",
            "/Story-Bytes-/img/receitas/agua-saborcitrico.jpg"
        ];
        
        echo "<table><tr><th>Caminho</th><th>Arquivo Existe?</th><th>Teste Visual</th></tr>";
        foreach ($caminhos_teste as $caminho) {
            $existe = file_exists($caminho);
            echo "<tr>";
            echo "<td>" . htmlspecialchars($caminho) . "</td>";
            echo "<td>" . ($existe ? "<span class='sucesso'>✅ SIM</span>" : "<span class='erro'>❌ NÃO</span>") . "</td>";
            echo "<td>";
            if ($existe) {
                echo "<img src='" . $caminho . "' alt='Teste' style='border: 2px solid green;'>";
            } else {
                echo "<img src='" . $caminho . "' alt='Teste' style='border: 2px solid red; background: #ffeeee;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"inline\"'>";
                echo "<span style='display:none; color: red;'>❌ Falha ao carregar</span>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>

    <div class="debug-section">
        <h2>5. Informações do Sistema</h2>
        <p><strong>Diretório atual:</strong> <?= getcwd() ?></p>
        <p><strong>Arquivo sendo executado:</strong> <?= __FILE__ ?></p>
        <p><strong>URL base:</strong> <?= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) ?></p>
    </div>

    <?php $conn->close(); ?>
</body>
</html>