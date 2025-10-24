<?php<?php

include('backend/conexao.php');include('backend/conexao.php');



echo "<h2>Receitas no Banco de Dados</h2>";echo "<h2>🔍 Verificação da estrutura da tabela 'receita'</h2>";



// Verificar quantas receitas existem// Verificar se a tabela existe e sua estrutura

$total_query = "SELECT COUNT(*) as total FROM receita";$result = $conn->query("DESCRIBE receita");

$result = $conn->query($total_query);

$total = $result->fetch_assoc()['total'];if ($result) {

echo "<p><strong>Total de receitas no banco: $total</strong></p>";    echo "<h3>✅ Estrutura da tabela 'receita':</h3>";

    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";

if ($total > 0) {    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th><th>Extra</th></tr>";

    echo "<h3>Lista de todas as receitas:</h3>";    

    $receitas = $conn->query("SELECT id, titulo, descricao, modoprep FROM receita ORDER BY id");    while ($row = $result->fetch_assoc()) {

            echo "<tr>";

    $contador = 1;        echo "<td>" . $row['Field'] . "</td>";

    while($receita = $receitas->fetch_assoc()) {        echo "<td>" . $row['Type'] . "</td>";

        echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; background-color: #f9f9f9;'>";        echo "<td>" . $row['Null'] . "</td>";

        echo "<h4>$contador. " . htmlspecialchars($receita['titulo']) . " (ID: {$receita['id']})</h4>";        echo "<td>" . $row['Key'] . "</td>";

        echo "<p><strong>Descrição:</strong> " . htmlspecialchars(substr($receita['descricao'], 0, 150)) . "...</p>";        echo "<td>" . $row['Default'] . "</td>";

        echo "<p><strong>Modo de Preparo:</strong> " . htmlspecialchars(substr($receita['modoprep'], 0, 200)) . "...</p>";        echo "<td>" . $row['Extra'] . "</td>";

        echo "</div>";        echo "</tr>";

        $contador++;    }

    }    echo "</table>";

    } else {

    echo "<hr>";    echo "❌ Tabela 'receita' não existe ou erro: " . $conn->error;

    echo "<h3>Teste de Busca:</h3>";}

    echo "<p>Agora vou testar alguns termos de busca comuns:</p>";

    echo "<hr>";

    // Testar termos comuns

    $termos_teste = ['bolo', 'frango', 'massa', 'chocolate', 'receita'];// Verificar se há receitas

    $count_result = $conn->query("SELECT COUNT(*) as total FROM receita");

    foreach($termos_teste as $termo) {if ($count_result) {

        echo "<h4>Buscando por: '$termo'</h4>";    $count = $count_result->fetch_assoc();

            echo "<h3>📊 Total de receitas no banco: " . $count['total'] . "</h3>";

        $sql = "SELECT titulo FROM receita WHERE titulo LIKE ? OR descricao LIKE ? OR modoprep LIKE ?";    

        $termo_like = "%{$termo}%";    if ($count['total'] > 0) {

                echo "<h3>🔍 Primeiras 5 receitas:</h3>";

        $stmt = $conn->prepare($sql);        $receitas = $conn->query("SELECT id, titulo, usuario_id, status_aprovacao, datacriacao FROM receita LIMIT 5");

        $stmt->bind_param("sss", $termo_like, $termo_like, $termo_like);        

        $stmt->execute();        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";

        $result = $stmt->get_result();        echo "<tr><th>ID</th><th>Título</th><th>Usuário ID</th><th>Status</th><th>Data Criação</th></tr>";

                

        if ($result->num_rows > 0) {        while ($receita = $receitas->fetch_assoc()) {

            echo "<ul>";            echo "<tr>";

            while($row = $result->fetch_assoc()) {            echo "<td>" . $receita['id'] . "</td>";

                echo "<li>" . htmlspecialchars($row['titulo']) . "</li>";            echo "<td>" . htmlspecialchars($receita['titulo']) . "</td>";

            }            echo "<td>" . $receita['usuario_id'] . "</td>";

            echo "</ul>";            echo "<td>" . $receita['status_aprovacao'] . "</td>";

        } else {            echo "<td>" . $receita['datacriacao'] . "</td>";

            echo "<p style='color: red;'>Nenhum resultado encontrado.</p>";            echo "</tr>";

        }        }

    }        echo "</table>";

        }

} else {} else {

    echo "<p style='color: red;'>Não há receitas no banco de dados!</p>";    echo "❌ Erro ao contar receitas: " . $conn->error;

    echo "<p>Você precisa adicionar algumas receitas primeiro.</p>";}

}

$conn->close();

$conn->close();?>
?>