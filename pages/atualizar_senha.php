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
                'css/atualizar-senha.css'
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
    
    // Verificar senha atual apenas se não há erros básicos
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
            }
        } catch (Exception $e) {
            $erros[] = "Erro ao verificar senha atual";
        }
    }
    
    if (empty($erros)) {
        try {
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
                            <h2>Senha alterada com sucesso!</h2>
                            <p>Sua senha foi atualizada com segurança.</p>
                            <p><strong>Recomendação:</strong> Guarde sua nova senha em local seguro.</p>
                            <div class="acoes-senha">
                                <a href="perfil.php" class="btn-primary">Voltar ao Perfil</a>
                                <a href="logout.php" class="btn-secondary">Fazer Login Novamente</a>
                            </div>
                        </div>
                    </main>';
                    
                    require_once APP_ROOT . '/partials/_footer.php';
                    $conn->close();
                    exit(); // Importante: sair aqui para não continuar executando
                    
                } else {
                    throw new Exception("Erro ao atualizar senha: " . $stmt->error);
                }
            
        } catch (Exception $e) {
            echo '
            <main class="formulario">
                <div class="mensagem-erro">
                    <h2>Erro ao alterar senha</h2>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p><a href="perfil.php">Voltar ao perfil</a></p>
                </div>
            </main>';
            
            require_once APP_ROOT . '/partials/_footer.php';
            $conn->close();
            exit(); // Importante: sair aqui para não continuar executando
        }
    }
    
    if (!empty($erros)) {
        // Exibir erros de validação e formulário novamente
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>Erro na validação</h2>
                <ul>';
        
        foreach ($erros as $erro) {
            echo '<li>' . htmlspecialchars($erro) . '</li>';
        }
        
        echo '
                </ul>
            </div>
        </main>';
        
        // Exibir formulário novamente
        exibirFormulario();
    }
    
} else {
    // Primeira visita - exibir formulário
    exibirFormulario();
}

function exibirFormulario() {
    echo '
    <main class="formulario">
        <div class="form-container">
            <h1>Alterar Senha</h1>
            <p>Digite sua senha atual e escolha uma nova senha segura.</p>
            
            <form method="POST" action="atualizar_senha.php" class="form-alterar-senha">
                <div class="form-group">
                    <label for="senha_atual">Senha Atual *</label>
                    <input type="password" id="senha_atual" name="senha_atual" required 
                           placeholder="Digite sua senha atual">
                </div>
                
                <div class="form-group">
                    <label for="nova_senha">Nova Senha *</label>
                    <input type="password" id="nova_senha" name="nova_senha" required 
                           placeholder="Digite a nova senha (mínimo 6 caracteres)" 
                           minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha *</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                           placeholder="Digite novamente a nova senha">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Alterar Senha</button>
                    <a href="perfil.php" class="btn-secondary">Cancelar</a>
                </div>
            </form>
            
            <div class="senha-dicas">
                <h3>Dicas para uma senha segura:</h3>
                <ul>
                    <li>Use pelo menos 6 caracteres</li>
                    <li>Combine letras, números e símbolos</li>
                    <li>Evite informações pessoais óbvias</li>
                    <li>Não reutilize senhas de outros sites</li>
                </ul>
            </div>
        </div>
    </main>
    
    <script>
    // Validação em tempo real da confirmação de senha
    document.addEventListener("DOMContentLoaded", function() {
        const novaSenha = document.getElementById("nova_senha");
        const confirmarSenha = document.getElementById("confirmar_senha");
        const form = document.querySelector(".form-alterar-senha");
        
        function validarSenhas() {
            if (confirmarSenha.value && novaSenha.value) {
                if (novaSenha.value === confirmarSenha.value) {
                    confirmarSenha.style.borderColor = "#28a745";
                    confirmarSenha.style.boxShadow = "0 0 0 3px rgba(40, 167, 69, 0.1)";
                } else {
                    confirmarSenha.style.borderColor = "#dc3545";
                    confirmarSenha.style.boxShadow = "0 0 0 3px rgba(220, 53, 69, 0.1)";
                }
            } else {
                confirmarSenha.style.borderColor = "#dee2e6";
                confirmarSenha.style.boxShadow = "none";
            }
        }
        
        novaSenha.addEventListener("input", validarSenhas);
        confirmarSenha.addEventListener("input", validarSenhas);
        
        // Validação no submit
        form.addEventListener("submit", function(e) {
            if (novaSenha.value !== confirmarSenha.value) {
                e.preventDefault();
                alert("As senhas não conferem. Por favor, verifique.");
                confirmarSenha.focus();
                return false;
            }
            
            if (novaSenha.value.length < 6) {
                e.preventDefault();
                alert("A nova senha deve ter pelo menos 6 caracteres.");
                novaSenha.focus();
                return false;
            }
            
            // Confirmação antes de enviar
            if (!confirm("Tem certeza que deseja alterar sua senha?")) {
                e.preventDefault();
                return false;
            }
        });
    });
    </script>';
}

require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>