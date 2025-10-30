<?php
include('backend/conexao.php');

echo "<h2>Verificando e adicionando coluna usuario_id se necessário</h2>";

// Verificar se a coluna usuario_id existe
$check_column = $conn->query("SHOW COLUMNS FROM receita LIKE 'usuario_id'");

if ($check_column->num_rows == 0) {
    echo "<p>Coluna usuario_id não encontrada. Adicionando...</p>";
    
    // Adicionar a coluna usuario_id
    $add_column = $conn->query("ALTER TABLE receita ADD COLUMN usuario_id INT(11) DEFAULT 1 AFTER id");
    
    if ($add_column) {
        echo "<p style='color: green;'>✓ Coluna usuario_id adicionada com sucesso!</p>";
        
        // Atualizar todas as receitas existentes para pertencerem ao usuário com ID 1
        $update_existing = $conn->query("UPDATE receita SET usuario_id = 1 WHERE usuario_id IS NULL OR usuario_id = 0");
        
        if ($update_existing) {
            echo "<p style='color: green;'>✓ Receitas existentes atribuídas ao usuário ID 1</p>";
        } else {
            echo "<p style='color: red;'>✗ Erro ao atualizar receitas existentes: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Erro ao adicionar coluna: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ Coluna usuario_id já existe!</p>";
}

// Verificar novamente a estrutura
echo "<h3>Estrutura atual da tabela receita:</h3>";
$resultado = $conn->query("DESCRIBE receita");

if ($resultado) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr style='background-color: #f0f0f0;'><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

$conn->close();
?>