<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Story-Bytes-/pages/login.php");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Alterando Senha';
$PAGE_DESC   = 'Alterando sua senha de acesso.';
$PAGE_STYLES = [
                'css/login.css',
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
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
    
    if ($nova_senha !== $confirmar_senha) {
        $erros[] = "Confirmação de senha não confere";
    }
    
    if (empty($erros)) {
        try {
            // Buscar senha atual do usuário
            $sql_get = "SELECT senha FROM usuario WHERE id = ?";
            $stmt_get = $conn->prepare($sql_get);
            $stmt_get->bind_param("i", $usuario_id);
            $stmt_get->execute();
            $result = $stmt_get->get_result();
            $usuario_atual = $result->fetch_assoc();
            
            // Verificar se a senha atual está correta
            if (!password_verify($senha_atual, $usuario_atual['senha'])) {
                $erros[] = "Senha atual incorreta";
            } else {
                // Criptografar nova senha
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                
                // Atualizar senha no banco
                $sql = "UPDATE usuario SET senha = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $nova_senha_hash, $usuario_id);
                
                if ($stmt->execute()) {
                    echo '
                    <main class="formulario">
                        <div class="mensagem-sucesso">
                            <h2>🔐 Senha alterada com sucesso!</h2>
                            <p>Sua senha foi atualizada com segurança.</p>
                            <p><strong>Recomendação:</strong> Guarde sua nova senha em local seguro.</p>
                            <div style="margin-top: 20px;">
                                <a href="perfil.php" class="btn-primary">👤 Voltar ao Perfil</a>
                                <a href="logout.php" class="btn-secondary" style="margin-left: 10px;">🚪 Fazer Login Novamente</a>
                            </div>
                        </div>
                    </main>';
                    
                } else {
                    throw new Exception("Erro ao atualizar senha: " . $stmt->error);
                }
            }
            
        } catch (Exception $e) {
            echo '
            <main class="formulario">
                <div class="mensagem-erro">
                    <h2>❌ Erro ao alterar senha</h2>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p><a href="perfil.php">Voltar ao perfil</a></p>
                </div>
            </main>';
        }
    }
    
    if (!empty($erros)) {
        // Exibir erros de validação
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>❌ Erro na validação</h2>
                <ul style="text-align: left; margin: 15px 0;">';
        
        foreach ($erros as $erro) {
            echo '<li>' . htmlspecialchars($erro) . '</li>';
        }
        
        echo '
                </ul>
                <p><a href="perfil.php">Voltar e corrigir</a></p>
            </div>
        </main>';
    }
    
} else {
    // Se não foi POST, redirecionar para o perfil
    header("Location: perfil.php");
    exit();
}

require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>