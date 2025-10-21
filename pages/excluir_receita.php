<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

include('../backend/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $receita_id = (int)$_POST['id'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // Verificar se a receita pertence ao usuário e obter dados para excluir imagem
    $sql_check = "SELECT imagem FROM receita WHERE id = ? AND usuario_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $receita_id, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($receita = $result_check->fetch_assoc()) {
        // Excluir receita do banco
        $sql = "DELETE FROM receita WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $receita_id, $usuario_id);
        
        if ($stmt->execute()) {
            // Se tinha imagem, tentar excluir o arquivo
            if ($receita['imagem'] && file_exists('../img/receitas/' . $receita['imagem'])) {
                unlink('../img/receitas/' . $receita['imagem']);
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Receita excluída com sucesso'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao excluir receita'
            ]);
        }
        
        $stmt->close();
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Receita não encontrada'
        ]);
    }
    
    $stmt_check->close();
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'ID da receita não fornecido'
    ]);
}

$conn->close();
?>