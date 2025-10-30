<?php
session_start();
header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

require_once __DIR__ . '/../config.php';
include('../backend/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    
    // Validações
    $erros = [];
    
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório";
    }
    
    if (empty($email)) {
        $erros[] = "E-mail é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido";
    }
    
    // Verificar se o e-mail já existe para outro usuário
    $sql_check = "SELECT id FROM usuario WHERE email = ? AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        $erros[] = "Este e-mail já está sendo usado por outro usuário";
    }
    
    if (empty($erros)) {
        try {
            // Atualizar dados no banco
            $sql = "UPDATE usuario SET nome = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $usuario_id);
            
            if ($stmt->execute()) {
                // Atualizar dados na sessão
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Dados atualizados com sucesso',
                    'nome' => $nome,
                    'email' => $email
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar dados no banco']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $erros)]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}

$conn->close();
?>