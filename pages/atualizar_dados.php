<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Story-Bytes-/pages/login.php");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Atualizando Dados';
$PAGE_DESC   = 'Atualizando suas informações pessoais.';
$PAGE_STYLES = [
                'css/login.css',
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
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
            // Buscar dados antigos para comparação
            $sql_old = "SELECT nome, email FROM usuario WHERE id = ?";
            $stmt_old = $conn->prepare($sql_old);
            $stmt_old->bind_param("i", $usuario_id);
            $stmt_old->execute();
            $dados_antigos = $stmt_old->get_result()->fetch_assoc();
            
            // Atualizar dados no banco
            $sql = "UPDATE usuario SET nome = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $usuario_id);
            
            if ($stmt->execute()) {
                // Verificar se realmente houve alteração
                $alteracoes = [];
                if ($dados_antigos['nome'] !== $nome) {
                    $alteracoes[] = "Nome: '{$dados_antigos['nome']}' → '{$nome}'";
                }
                if ($dados_antigos['email'] !== $email) {
                    $alteracoes[] = "E-mail: '{$dados_antigos['email']}' → '{$email}'";
                }
                
                // Atualizar dados na sessão
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;
                
                echo '
                <main class="formulario">
                    <div class="mensagem-sucesso">
                        <h2>Dados atualizados com sucesso!</h2>
                        <p>Suas informações foram atualizadas:</p>
                        <ul style="text-align: left; margin: 15px 0;">
                            <li><strong>Nome:</strong> ' . htmlspecialchars($nome) . '</li>
                            <li><strong>E-mail:</strong> ' . htmlspecialchars($email) . '</li>
                        </ul>
                        ' . (!empty($alteracoes) ? '<p><strong>Alterações feitas:</strong><br>' . implode('<br>', $alteracoes) . '</p>' : '<p>Nenhuma alteração foi detectada.</p>') . '
                        <p><small>As alterações são aplicadas imediatamente em todo o site.</small></p>
                        <div style="margin-top: 20px;">
                            <a href="perfil.php?tab=dados" class="btn-primary">Voltar ao Perfil</a>
                        </div>
                    </div>
                    <script>
                        // Atualizar header automaticamente após 2 segundos
                        setTimeout(function() {
                            if (window.location.href.indexOf("perfil.php") === -1) {
                                window.location.href = "perfil.php?tab=dados";
                            }
                        }, 3000);
                    </script>
                </main>';
                
            } else {
                throw new Exception("Erro ao atualizar dados: " . $stmt->error);
            }
            
        } catch (Exception $e) {
            echo '
            <main class="formulario">
                <div class="mensagem-erro">
                    <h2>Erro ao atualizar dados</h2>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p><a href="perfil.php?tab=dados">Voltar ao perfil</a></p>
                </div>
            </main>';
        }
    } else {
        // Exibir erros de validação
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>Erro na validação</h2>
                <ul style="text-align: left; margin: 15px 0;">';
        
        foreach ($erros as $erro) {
            echo '<li>' . htmlspecialchars($erro) . '</li>';
        }
        
        echo '
                </ul>
                <p><a href="perfil.php?tab=dados">Voltar e corrigir</a></p>
            </div>
        </main>';
    }
    
} else {
    // Se não foi POST, redirecionar para o perfil
    header("Location: perfil.php?tab=dados");
    exit();
}

require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>