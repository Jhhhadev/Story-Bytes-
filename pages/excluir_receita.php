<?php
session_start();

// Definir cabeçalho JSON
header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

require_once __DIR__ . '/../config.php';
include('../backend/conexao.php');

// Verificar se é POST e se tem ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método incorreto']);
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID da receita não fornecido']);
    exit();
}

$receita_id = (int)$_POST['id'];
$usuario_id = $_SESSION['usuario_id'];

if ($receita_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID da receita inválido']);
    exit();
}

try {
    // Verificar se a receita existe
    $sql_check = "SELECT id, titulo, usuario_id FROM receita WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    
    if (!$stmt_check) {
        throw new Exception('Erro ao preparar consulta de verificação');
    }
    
    $stmt_check->bind_param("i", $receita_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Receita não encontrada']);
        exit();
    }
    
    $receita = $result->fetch_assoc();
    
    // Verificar se a receita pertence ao usuário logado (ou se é admin)
    if ($receita['usuario_id'] != $usuario_id && $_SESSION['usuario_tipo'] !== 'admin') {
        echo json_encode([
            'success' => false, 
            'message' => 'Você não tem permissão para excluir esta receita',
            'debug' => [
                'receita_usuario_id' => $receita['usuario_id'],
                'session_usuario_id' => $usuario_id,
                'usuario_tipo' => $_SESSION['usuario_tipo'] ?? 'não definido'
            ]
        ]);
        exit();
    }
    
    // Excluir a receita
    $sql_delete = "DELETE FROM receita WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    
    if (!$stmt_delete) {
        throw new Exception('Erro ao preparar consulta de exclusão');
    }
    
    $stmt_delete->bind_param("i", $receita_id);
    
    if ($stmt_delete->execute()) {
        $affected = $stmt_delete->affected_rows;
        
        if ($affected > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Receita "' . htmlspecialchars($receita['titulo']) . '" excluída com sucesso!'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma receita foi excluída']);
        }
    } else {
        throw new Exception('Erro ao executar exclusão: ' . $stmt_delete->error);
    }
    
    $stmt_delete->close();
    $stmt_check->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
}

$conn->close();
?>