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
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações
    $erros = [];
    
    if (empty($senha_atual)) {
        $erros[] = "Senha atual é obrigatória";
    }
    
    if (empty($nova_senha)) {
        $erros[] = "Nova senha é obrigatória";
    } elseif (strlen($nova_senha) < 6) {
        $erros[] = "Nova senha deve ter pelo menos 6 caracteres";
    }
    
    if (empty($confirmar_senha)) {
        $erros[] = "Confirmação da senha é obrigatória";
    }
    
    if ($nova_senha !== $confirmar_senha) {
        $erros[] = "Confirmação da senha não confere";
    }
    
    if (empty($erros)) {
        try {
            // Verificar senha atual
            $sql_check = "SELECT senha FROM usuario WHERE id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("i", $usuario_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $usuario = $result->fetch_assoc();
            
            if (!$usuario) {
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
                exit();
            }
            
            // Verificar se a senha atual está correta
            if (!password_verify($senha_atual, $usuario['senha'])) {
                echo json_encode(['success' => false, 'message' => 'Senha atual incorreta']);
                exit();
            }
            
            // Gerar hash da nova senha
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            // Atualizar senha no banco
            $sql_update = "UPDATE usuario SET senha = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $nova_senha_hash, $usuario_id);
            
            if ($stmt_update->execute()) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Senha alterada com sucesso'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar senha no banco']);
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