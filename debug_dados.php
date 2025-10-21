<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "❌ Faça login primeiro em: <a href='pages/login.php'>Login</a>";
    exit();
}

include('backend/conexao.php');

$usuario_id = $_SESSION['usuario_id'];

echo "<h2>🔍 Debug: Dados do Usuário</h2>";

// Dados da sessão
echo "<h3>📱 Dados na Sessão:</h3>";
echo "<p><strong>ID:</strong> " . $_SESSION['usuario_id'] . "</p>";
echo "<p><strong>Nome:</strong> " . htmlspecialchars($_SESSION['usuario_nome']) . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($_SESSION['usuario_email']) . "</p>";
echo "<p><strong>Tipo:</strong> " . $_SESSION['usuario_tipo'] . "</p>";

echo "<hr>";

// Dados do banco
echo "<h3>💾 Dados no Banco de Dados:</h3>";
$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$dados_banco = $stmt->get_result()->fetch_assoc();

if ($dados_banco) {
    echo "<p><strong>ID:</strong> " . $dados_banco['id'] . "</p>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($dados_banco['nome']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($dados_banco['email']) . "</p>";
    echo "<p><strong>Tipo:</strong> " . $dados_banco['tipo_usuario'] . "</p>";
    echo "<p><strong>Data Cadastro:</strong> " . $dados_banco['dataCadastro'] . "</p>";
} else {
    echo "❌ Usuário não encontrado no banco!";
}

echo "<hr>";

// Comparação
echo "<h3>🔍 Comparação:</h3>";
if ($dados_banco) {
    $sessao_nome = $_SESSION['usuario_nome'];
    $banco_nome = $dados_banco['nome'];
    $sessao_email = $_SESSION['usuario_email'];
    $banco_email = $dados_banco['email'];
    
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 20px;'>";
    
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 8px;'>";
    echo "<h4 style='color: blue;'>📱 Sessão</h4>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($sessao_nome) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($sessao_email) . "</p>";
    echo "</div>";
    
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 8px;'>";
    echo "<h4 style='color: green;'>💾 Banco</h4>";
    echo "<p><strong>Nome:</strong> " . htmlspecialchars($banco_nome) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($banco_email) . "</p>";
    echo "</div>";
    
    echo "</div>";
    
    // Status de sincronização
    $nome_sincronizado = ($sessao_nome === $banco_nome);
    $email_sincronizado = ($sessao_email === $banco_email);
    
    echo "<h4>📊 Status de Sincronização:</h4>";
    echo "<p><strong>Nome:</strong> " . ($nome_sincronizado ? "✅ Sincronizado" : "❌ Dessincronizado") . "</p>";
    echo "<p><strong>Email:</strong> " . ($email_sincronizado ? "✅ Sincronizado" : "❌ Dessincronizado") . "</p>";
    
    if (!$nome_sincronizado || !$email_sincronizado) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 15px 0;'>";
        echo "<h4>⚠️ Dados Dessincronizados!</h4>";
        echo "<p>A sessão e o banco estão com dados diferentes. Isso pode causar problemas na exibição.</p>";
        echo "<button onclick='sincronizarDados()' style='background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;'>🔄 Sincronizar Agora</button>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h3>🔧 Ações:</h3>";
echo "<p><a href='pages/perfil.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>👤 Ir para Perfil</a>";
echo "<a href='pages/logout.php' style='background: #dc3545; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🚪 Logout</a></p>";

$stmt->close();
$conn->close();
?>

<script>
function sincronizarDados() {
    if (confirm('🔄 Sincronizar dados da sessão com o banco de dados?')) {
        fetch('sincronizar_sessao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Dados sincronizados com sucesso!');
                location.reload();
            } else {
                alert('❌ Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao sincronizar dados');
        });
    }
}
</script>