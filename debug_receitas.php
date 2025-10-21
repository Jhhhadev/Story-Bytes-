<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "❌ Faça login primeiro em: <a href='pages/login.php'>Login</a>";
    exit();
}

include('backend/conexao.php');

$usuario_id = $_SESSION['usuario_id'];
echo "<h2>🔍 Debug: Minhas Receitas</h2>";
echo "<p><strong>Usuário logado ID:</strong> " . $usuario_id . "</p>";
echo "<p><strong>Nome:</strong> " . htmlspecialchars($_SESSION['usuario_nome']) . "</p>";

// Buscar receitas do usuário
$sql = "SELECT r.*, c.nome as categoria_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id
        WHERE r.usuario_id = ? 
        ORDER BY r.datacriacao DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>📊 Total de receitas suas: " . $result->num_rows . "</h3>";

if ($result->num_rows > 0) {
    echo "<h3>📋 Suas receitas:</h3>";
    echo "<div style='display: grid; gap: 20px;'>";
    
    while ($receita = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 8px;'>";
        echo "<h4>🍽️ " . htmlspecialchars($receita['titulo']) . "</h4>";
        echo "<p><strong>Status:</strong> " . $receita['status_aprovacao'] . "</p>";
        echo "<p><strong>Categoria:</strong> " . ($receita['categoria_nome'] ?? 'Sem categoria') . "</p>";
        echo "<p><strong>Criada em:</strong> " . date('d/m/Y H:i', strtotime($receita['datacriacao'])) . "</p>";
        echo "<p><strong>Descrição:</strong> " . htmlspecialchars(substr($receita['descricao'], 0, 150)) . "...</p>";
        
        if ($receita['imagem']) {
            echo "<p><strong>Imagem:</strong> " . $receita['imagem'] . "</p>";
        }
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3>🍽️ Nenhuma receita encontrada</h3>";
    echo "<p>Você ainda não criou nenhuma receita!</p>";
    echo "<p><a href='pages/perfil.php' style='background: #E85A4F; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>➕ Criar Primeira Receita</a></p>";
    echo "</div>";
}

$stmt->close();
$conn->close();
?>