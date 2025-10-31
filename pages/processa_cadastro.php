<?php
// ==========================================
// SISTEMA DE CADASTRO - VERSÃO CORRIGIDA
// ==========================================

$ACTIVE_PAGE = 'cadastro';
$PAGE_TITLE  = 'StoryBites — Processando Cadastro';
$PAGE_DESC   = 'Finalizando seu cadastro no StoryBites.';
$PAGE_STYLES = ['css/login.css', 'css/processa-cadastro.css'];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';

// Verificar se dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
    echo '
    <main class="formulario">
        <div class="message-container">
            <h2 class="message-title">Acesso Inválido</h2>
            <p class="message-text">Esta página só pode ser acessada através do formulário de cadastro.</p>
            <a href="pages/cadastro.php" class="btn-action primary">Voltar ao Cadastro</a>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    exit();
}

// Incluir conexão com banco de dados
include('../backend/conexao.php');

// Verificar se conexão foi estabelecida
if (!$conn) {
    echo '
    <main class="formulario">
        <div class="message-container">
            <h2 class="message-title error">Erro de Conexão</h2>
            <p class="message-text">Não foi possível conectar ao banco de dados.</p>
            <a href="pages/cadastro.php" class="btn-action primary">Tentar Novamente</a>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    exit();
}

// Sanitizar e validar dados do formulário
$nome = trim($_POST['nome']);
$email = trim(strtolower($_POST['email']));
$senha = $_POST['senha'];

// Validações básicas
$erros = [];

if (strlen($nome) < 2) {
    $erros[] = "Nome deve ter pelo menos 2 caracteres.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "Email inválido.";
}

if (strlen($senha) < 6) {
    $erros[] = "Senha deve ter pelo menos 6 caracteres.";
}

// Se houver erros de validação
if (!empty($erros)) {
    echo '
    <main class="formulario">
        <div class="error-container">
            <div class="icon-circle error">!</div>
            <h2 class="message-title error">Dados Inválidos</h2>';
    
    foreach ($erros as $erro) {
        echo '<p class="error-list-item">• ' . $erro . '</p>';
    }
    
    echo '
            <a href="pages/cadastro.php" class="btn-action primary">Corrigir Dados</a>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    exit();
}

// Verificar se email já existe no banco de dados
$sql_check = "SELECT id FROM usuario WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);

if (!$stmt_check) {
    echo '
    <main class="formulario">
        <div class="message-container">
            <h2 class="message-title error">Erro no Banco</h2>
            <p class="message-text">Erro ao preparar consulta: ' . $conn->error . '</p>
            <a href="pages/cadastro.php" class="btn-action primary">Tentar Novamente</a>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    exit();
}

$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Email já cadastrado - mostrar mensagem de erro
    $stmt_check->close();
    echo '
    <main class="formulario">
        <div class="email-duplicate-container">
            <div class="icon-circle warning">×</div>
            <h2 class="message-title">Email já cadastrado</h2>
            <p class="message-text primary">Este email já está sendo usado por outro usuário.</p>
            <p class="message-text secondary">Verifique o endereço digitado ou faça login com sua conta existente.</p>
            <div class="button-container">
                <a href="pages/cadastro.php" class="btn-action secondary">Tentar novamente</a>
                <a href="pages/login.php" class="btn-action primary">Fazer Login</a>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "pages/cadastro.php";
                }, 8000);
            </script>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    $conn->close();
    exit();
}

$stmt_check->close();

// Email disponível - prosseguir com o cadastro
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$data_cadastro = date('Y-m-d');
$tipo_usuario = 'comum';

$sql_insert = "INSERT INTO usuario (nome, email, senha, dataCadastro, tipo_usuario) VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);

if (!$stmt_insert) {
    echo '
    <main class="formulario">
        <div class="message-container">
            <h2 class="message-title error">Erro no Banco</h2>
            <p class="message-text">Erro ao preparar inserção: ' . $conn->error . '</p>
            <a href="pages/cadastro.php" class="btn-action primary">Tentar Novamente</a>
        </div>
    </main>';
    require_once APP_ROOT . '/partials/_footer.php';
    exit();
}

$stmt_insert->bind_param("sssss", $nome, $email, $senha_hash, $data_cadastro, $tipo_usuario);

if ($stmt_insert->execute()) {
    // Cadastro realizado com sucesso!
    $stmt_insert->close();
    echo '
    <main class="formulario">
        <div class="success-container">
            <div class="icon-circle success">✓</div>
            <h2 class="message-title success">Cadastro realizado com sucesso!</h2>
            <p class="message-text success-welcome">Bem-vindo(a), <strong class="highlight-text">' . htmlspecialchars($nome) . '</strong>!</p>
            <p class="message-text info">Sua conta foi criada com sucesso.</p>
            <p class="message-text countdown">Você será redirecionado para a página inicial em <span id="countdown" class="highlight-text">5</span> segundos...</p>
            <p class="message-text">Se não for redirecionado, <a href="../index.php" class="inline-link">clique aqui</a>.</p>
            <script>
                let countdown = 5;
                const countdownElement = document.getElementById("countdown");
                const timer = setInterval(() => {
                    countdown--;
                    countdownElement.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        window.location.href = "../index.php";
                    }
                }, 1000);
            </script>
        </div>
    </main>';
} else {
    // Erro na inserção
    $stmt_insert->close();
    echo '
    <main class="formulario">
        <div class="error-container">
            <div class="icon-circle warning">×</div>
            <h2 class="message-title">Erro ao cadastrar</h2>
            <p class="message-text primary">Ocorreu um erro durante o cadastro.</p>
            <p class="message-text small-text">Erro: ' . $conn->error . '</p>
            <a href="pages/cadastro.php" class="btn-action primary">Tentar Novamente</a>
        </div>
    </main>';
}

require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>