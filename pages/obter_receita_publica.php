<?php
include('../backend/conexao.php');

if (isset($_GET['id'])) {
    $receita_id = (int)$_GET['id'];
    
    // Buscar receita pública (aprovada)
    $sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
            FROM receita r 
            LEFT JOIN categoria c ON r.categoria_id = c.id 
            LEFT JOIN usuario u ON r.usuario_id = u.id 
            WHERE r.id = ? AND r.status_aprovacao = 'aprovada'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $receita_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($receita = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'receita' => $receita
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Receita não encontrada ou não aprovada'
        ]);
    }
    
    $stmt->close();
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'ID da receita não fornecido'
    ]);
}

$conn->close();
?>