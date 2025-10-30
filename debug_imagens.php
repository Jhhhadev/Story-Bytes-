<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificar Imagens do Banco</title>
</head>
<body>
    <h1>Verificação de Imagens no Banco de Dados</h1>
    
    <?php
    include('backend/conexao.php');
    
    $sql = "SELECT id, nome, imagem FROM receita WHERE imagem IS NOT NULL AND imagem != '' LIMIT 10";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Imagem</th><th>Arquivo Existe?</th><th>Preview</th></tr>";
        
        while($row = $result->fetch_assoc()) {
            $imagePath = "img/receitas/" . $row['imagem'];
            $fileExists = file_exists($imagePath) ? "✅ Sim" : "❌ Não";
            
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['imagem']) . "</td>";
            echo "<td>" . $fileExists . "</td>";
            echo "<td>";
            if (file_exists($imagePath)) {
                echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($row['nome']) . "' style='width: 100px; height: 60px; object-fit: cover;'>";
            } else {
                echo "❌ Imagem não encontrada";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhuma receita com imagem encontrada no banco de dados.</p>";
    }
    
    $conn->close();
    ?>
</body>
</html>