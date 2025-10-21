<?php
include('backend/conexao.php');

echo "<h2>🔍 Verificação da estrutura da tabela 'receita'</h2>";

// Verificar se a tabela existe e sua estrutura
$result = $conn->query("DESCRIBE receita");

if ($result) {
    echo "<h3>✅ Estrutura da tabela 'receita':</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tabela 'receita' não existe ou erro: " . $conn->error;
}

echo "<hr>";

// Verificar se há receitas
$count_result = $conn->query("SELECT COUNT(*) as total FROM receita");
if ($count_result) {
    $count = $count_result->fetch_assoc();
    echo "<h3>📊 Total de receitas no banco: " . $count['total'] . "</h3>";
    
    if ($count['total'] > 0) {
        echo "<h3>🔍 Primeiras 5 receitas:</h3>";
        $receitas = $conn->query("SELECT id, titulo, usuario_id, status_aprovacao, datacriacao FROM receita LIMIT 5");
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Título</th><th>Usuário ID</th><th>Status</th><th>Data Criação</th></tr>";
        
        while ($receita = $receitas->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $receita['id'] . "</td>";
            echo "<td>" . htmlspecialchars($receita['titulo']) . "</td>";
            echo "<td>" . $receita['usuario_id'] . "</td>";
            echo "<td>" . $receita['status_aprovacao'] . "</td>";
            echo "<td>" . $receita['datacriacao'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "❌ Erro ao contar receitas: " . $conn->error;
}

$conn->close();
?>