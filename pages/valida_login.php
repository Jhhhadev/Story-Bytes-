<?php
session_start();

$ACTIVE_PAGE = 'login';
$PAGE_TITLE  = 'StoryBites — Validando Login';
$PAGE_DESC   = 'Processando seu login no StoryBites.';
$PAGE_STYLES = [
                'css/login.css',
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';

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
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];

        // Redirecionamento imediato com header (mais confiável)
        header("Location: ../index.php");
        exit();
        
        // Caso o header não funcione, fallback com meta refresh
        echo '
        <main class="formulario">
            <div class="mensagem-sucesso">
                <h2>Login realizado com sucesso!</h2>
                <p>Bem-vindo, ' . htmlspecialchars($usuario['nome']) . '!</p>
                <p>Redirecionando para a página inicial...</p>
            </div>
            <meta http-equiv="refresh" content="2;URL=../index.php">
        </main>
        ';
    } else {
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>Senha incorreta</h2>
                <p>A senha informada não confere com nossos registros.</p>
                <p>Verifique se não há erro de digitação e tente novamente.</p>
                <a href="/Story-Bytes-/pages/login.php">Tentar novamente</a>
            </div>
            <script>
                // Auto-redirecionar após 5 segundos se o usuário não clicar
                setTimeout(function() {
                    if (!document.hidden) {
                        window.location.href = "/Story-Bytes-/pages/login.php";
                    }
                }, 5000);
            </script>
        </main>';
    }
} else {
    echo '
    <main class="formulario">
        <div class="mensagem-erro">
            <h2>E-mail não encontrado</h2>
            <p>Não encontramos uma conta com este e-mail.</p>
            <p>Verifique o endereço digitado ou crie uma nova conta.</p>
            <div style="margin-top: 20px;">
                <a href="/Story-Bytes-/pages/login.php" style="margin-right: 10px;">Tentar novamente</a>
                <a href="/Story-Bytes-/pages/cadastro.php" style="background-color: var(--cor-primaria); color: white; padding: 10px 20px; border-radius: 5px; margin-left: 10px;">Criar conta</a>
            </div>
        </div>
    </main>';
}

require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>