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
                'css/processa-receita.css',
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
    $acao = $_POST['acao'] ?? 'aprovar'; // padrão é enviar para aprovação
    
    // Definir status baseado na ação
    $status_aprovacao = ($acao === 'salvar') ? 'rascunho' : 'pendente';
    
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
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssssss", $usuario_id, $categoria_id, $titulo, $descricao, $ingredientes, $modo_preparo, $rendimento, $tempo_preparo, $imagem_nome, $status_aprovacao);
        
        if ($stmt->execute()) {
            if ($acao === 'salvar') {
                echo '
                <main class="formulario">
                    <div class="mensagem-sucesso">
                        <h2>Receita salva como rascunho!</h2>
                        <p><strong>' . htmlspecialchars($titulo) . '</strong> foi salva em seus rascunhos.</p>
                        <p>Você pode editá-la a qualquer momento ou enviá-la para aprovação quando quiser!</p>
                        <div class="botoes-acao">
                            <a href="/Story-Bytes-/pages/perfil.php?tab=minhas" class="btn-primary">Ver Minhas Receitas</a>
                            <a href="/Story-Bytes-/pages/perfil.php?tab=criar" class="btn-secondary">Criar Nova Receita</a>
                        </div>
                    </div>
                </main>';
            } else {
                echo '
                <main class="formulario">
                    <div class="mensagem-sucesso">
                        <h2>Receita enviada para aprovação!</h2>
                        <p><strong>' . htmlspecialchars($titulo) . '</strong> foi enviada para aprovação.</p>
                        <p>Nossos administradores irão revisar sua receita em breve!</p>
                        <div class="botoes-acao">
                            <a href="/Story-Bytes-/pages/perfil.php?tab=minhas" class="btn-primary">Ver Minhas Receitas</a>
                            <a href="/Story-Bytes-/pages/perfil.php?tab=criar" class="btn-secondary">Criar Nova Receita</a>
                        </div>
                    </div>
                </main>';
            }
            
        } else {
            throw new Exception("Erro ao salvar receita: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        echo '
        <main class="formulario">
            <div class="mensagem-erro">
                <h2>❌ Erro ao enviar receita</h2>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <div class="botoes-acao">
                    <a href="perfil.php" class="btn-primary">Tentar novamente</a>
                </div>
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