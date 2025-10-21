<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

include('../backend/conexao.php');

if (isset($_GET['id'])) {
    $receita_id = (int)$_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // Buscar receita do usuário logado (segurança)
    $sql = "SELECT r.*, c.nome as categoria_nome 
            FROM receita r 
            LEFT JOIN categoria c ON r.categoria_id = c.id 
            WHERE r.id = ? AND r.usuario_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $receita_id, $usuario_id);
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
            'message' => 'Receita não encontrada'
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