<?php
include('backend/conexao.php');

echo "<h2>Estrutura da tabela receita:</h2>";
$resultado = $conn->query("DESCRIBE receita");

if ($resultado) {
    echo "<table border='1'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $resultado->fetch_assoc()) {
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
    echo "Erro ao buscar estrutura da tabela: " . $conn->error;
}

echo "<hr><h2>Verificando uma receita específica:</h2>";
$teste = $conn->query("SELECT id, titulo, usuario_id FROM receita LIMIT 1");
if ($teste && $teste->num_rows > 0) {
    $receita = $teste->fetch_assoc();
    echo "<pre>" . print_r($receita, true) . "</pre>";
} else {
    echo "Nenhuma receita encontrada ou erro: " . $conn->error;
}

$conn->close();
?>