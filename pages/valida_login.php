<?php
session_start();
include('../backend/conexao.php'); 

// Receber os dados
$email = $_POST['email'];
$senha = $_POST['senha'];

// Verifica se o usuário existe - TABELA CORRETA
$sql = "SELECT * FROM usuario WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['senha'])) {
        // SESSÃO COMPLETA
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo_usuario']; // 0 ou 1

        echo '
        <div class="mensagem-sucesso">
            <h2>✅ Login realizado com sucesso!</h2>
            <p>Bem-vindo, ' . htmlspecialchars($usuario['nome']) . '!</p>
            <p>Redirecionando para a página inicial...</p>
        </div>
        <meta http-equiv="refresh" content="3;URL=../index.php">
        ';
    } else {
        echo '
        <div class="mensagem-erro">
            <h2>❌ Senha incorreta</h2>
            <p><a href="login.php">Tentar novamente</a></p>
        </div>';
    }
} else {
    echo '
    <div class="mensagem-erro">
        <h2>❌ E-mail não encontrado</h2>
        <p><a href="login.php">Tentar novamente</a></p>
    </div>';
}

$conn->close();
?>