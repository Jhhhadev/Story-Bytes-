<?php
include('../backend/conexao.php');        
include('../partials/_header.php');       

// Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$dataCadastro = date('Y-m-d');
$tipo_usuario = 0;

// Prepara a inserção
$sql = "INSERT INTO usuario (nome, email, senha, dataCadastro, tipo_usuario) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nome, $email, $senha, $dataCadastro, $tipo_usuario);

// Verifica resultado
if ($stmt->execute()) {
    echo '
    <div class="mensagem-sucesso">
        <h2>🎉 Cadastro realizado com sucesso!</h2>
        <p>Você será redirecionado para a página inicial em alguns segundos...</p>
        <p>Se não for redirecionado, <a href="../index.php">clique aqui</a>.</p>
    </div>
    <meta http-equiv="refresh" content="5;URL=../index.php">
    ';
} else {
    echo '<div class="mensagem-erro">
        <h2>❌ Erro ao cadastrar</h2>
        <p>' . $stmt->error . '</p>
        <p><a href="cadastro.php">Tentar novamente</a></p>
    </div>';
}

include('partials/_footer.php');    // ← MUDOU!
$conn->close();
?>