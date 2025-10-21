<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Story-Bytes-/pages/login.php");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Processando Receita';
$PAGE_DESC   = 'Enviando sua receita para aprovação.';
$PAGE_STYLES = [
                'css/login.css',
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $categoria_id = $_POST['categoria_id'];
    $descricao = $_POST['descricao'];
    $ingredientes = $_POST['ingredientes'];
    $modo_preparo = $_POST['modoprep'];
    $rendimento = $_POST['rendimento'] ?? '';
    $tempo_preparo = $_POST['tempo_preparo'] ?? '';
    
    // Processar upload de imagem (opcional)
    $imagem_nome = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../img/receitas/';
        
        // Criar diretório se não existir
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $arquivo_temp = $_FILES['imagem']['tmp_name'];
        $nome_original = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        
        // Verificar se é uma imagem válida
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($extensao, $extensoes_permitidas)) {
            // Gerar nome único para o arquivo
            $imagem_nome = 'receita_' . $usuario_id . '_' . time() . '.' . $extensao;
            $caminho_completo = $upload_dir . $imagem_nome;
            
            if (!move_uploaded_file($arquivo_temp, $caminho_completo)) {
                $imagem_nome = null; // Se falhar, continua sem imagem
            }
        }
    }
    
    try {
        // Inserir receita no banco de dados
        $sql = "INSERT INTO receita (usuario_id, categoria_id, titulo, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendente', NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssss", $usuario_id, $categoria_id, $titulo, $descricao, $ingredientes, $modo_preparo, $rendimento, $tempo_preparo, $imagem_nome);
        
        if ($stmt->execute()) {
            echo '
            <main class="formulario">
                <div class="mensagem-sucesso">
                    <h2>🎉 Receita enviada com sucesso!</h2>
                    <p><strong>' . htmlspecialchars($titulo) . '</strong> foi enviada para aprovação.</p>
                    <p>Nossos administradores irão revisar sua receita em breve!</p>
                    <div style="margin-top: 20px;">
                        <a href="perfil.php" class="btn-primary" style="margin-right: 10px;">📋 Ver Minhas Receitas</a>
                        <a href="perfil.php" class="btn-secondary">➕ Criar Nova Receita</a>
                    </div>
                </div>
            </main>';
            
        } else {
            throw new Exception("Erro ao salvar receita: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>❌ Erro ao enviar receita</h2>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <p><a href="perfil.php">Tentar novamente</a></p>
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