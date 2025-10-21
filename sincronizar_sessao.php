<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

include('backend/conexao.php');

$usuario_id = $_SESSION['usuario_id'];

// Buscar dados atualizados do banco
$sql = "SELECT nome, email, tipo_usuario FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($dados = $result->fetch_assoc()) {
    // Atualizar sessão com dados do banco
    $_SESSION['usuario_nome'] = $dados['nome'];
    $_SESSION['usuario_email'] = $dados['email'];
    $_SESSION['usuario_tipo'] = $dados['tipo_usuario'];
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Dados sincronizados com sucesso',
        'dados' => [
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'tipo' => $dados['tipo_usuario']
        ]
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não encontrado no banco de dados'
    ]);
}

$stmt->close();
$conn->close();
?>