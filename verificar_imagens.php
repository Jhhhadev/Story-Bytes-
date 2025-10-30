<?php
require_once 'backend/conexao.php';

$sql = "SELECT id, nome, imagem FROM receita WHERE imagem IS NOT NULL AND imagem != '' LIMIT 10";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " | Nome: " . $row['nome'] . " | Imagem: " . $row['imagem'] . "\n";
    }
} else {
    echo "Nenhuma receita com imagem encontrada.\n";
}

$conn->close();
?>