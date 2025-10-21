<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

include('../backend/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $receita_id = (int)$_POST['id'];
    $novo_status = $_POST['status'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // Validar status permitido
    $status_permitidos = ['rascunho', 'pendente'];
    if (!in_array($novo_status, $status_permitidos)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Status inválido']);
        exit();
    }
    
    // Verificar se a receita pertence ao usuário
    $sql_check = "SELECT id FROM receita WHERE id = ? AND usuario_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $receita_id, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows === 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Receita não encontrada']);
        exit();
    }
    
    // Atualizar status da receita
    $sql = "UPDATE receita SET status_aprovacao = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $novo_status, $receita_id, $usuario_id);
    
    if ($stmt->execute()) {
        $mensagem = ($novo_status === 'pendente') ? 'Receita enviada para aprovação' : 'Receita salva como rascunho';
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $mensagem
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao atualizar status da receita'
        ]);
    }
    
    $stmt->close();
    $stmt_check->close();
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos'
    ]);
}

$conn->close();
?>