<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Story-Bytes-/pages/login.php");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Editar Receita';
$PAGE_DESC   = 'Edite sua receita';
$PAGE_STYLES = [
                'css/login.css',
                'css/perfil.css',
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

$usuario_id = $_SESSION['usuario_id'];
$receita_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$receita_id) {
    header("Location: /Story-Bytes-/pages/perfil.php");
    exit();
}

// Buscar receita por ID
$sql = "SELECT r.*, c.nome as categoria_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id 
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receita_id);
$stmt->execute();
$receita = $stmt->get_result()->fetch_assoc();

if (!$receita) {
    header("Location: /Story-Bytes-/pages/perfil.php");
    exit();
}

// Buscar categorias
$sql_categorias = "SELECT * FROM categoria ORDER BY nome";
$categorias = $conn->query($sql_categorias);

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $modoprep = trim($_POST['modoprep']);
    $rendimento = trim($_POST['rendimento']);
    $categoria_id = (int)$_POST['categoria_id'];
    
    $errors = [];
    $nome_imagem = $receita['imagem']; // Manter imagem atual por padrão
    
    if (empty($titulo)) $errors[] = "Título é obrigatório";
    if (empty($descricao)) $errors[] = "Descrição é obrigatória";
    if (empty($modoprep)) $errors[] = "Modo de preparo é obrigatório";
    
    // Processar upload de imagem se fornecida
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem'];
        $extensoes_permitidas = ['jpg', 'jpeg', 'png'];
        $tamanho_maximo = 5 * 1024 * 1024; // 5MB
        
        // Verificar tamanho
        if ($arquivo['size'] > $tamanho_maximo) {
            $errors[] = "A imagem deve ter no máximo 5MB";
        }
        
        // Verificar extensão
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extensao, $extensoes_permitidas)) {
            $errors[] = "Formato de imagem não permitido. Use JPG, JPEG ou PNG";
        }
        
        if (empty($errors)) {
            // Gerar nome único para o arquivo
            $nome_imagem = 'receita_' . $receita_id . '_' . time() . '.' . $extensao;
            $caminho_destino = '../img/receitas/' . $nome_imagem;
            
            // Criar diretório se não existir
            if (!file_exists('../img/receitas/')) {
                mkdir('../img/receitas/', 0755, true);
            }
            
            // Mover arquivo
            if (!move_uploaded_file($arquivo['tmp_name'], $caminho_destino)) {
                $errors[] = "Erro ao fazer upload da imagem";
                $nome_imagem = $receita['imagem']; // Reverter para imagem anterior
            } else {
                // Remover imagem anterior se existir e for diferente
                if ($receita['imagem'] && $receita['imagem'] !== $nome_imagem) {
                    $caminho_anterior = '../img/receitas/' . $receita['imagem'];
                    if (file_exists($caminho_anterior)) {
                        unlink($caminho_anterior);
                    }
                }
            }
        }
    }
    
    if (empty($errors)) {
        $sql_update = "UPDATE receita SET titulo = ?, descricao = ?, modoprep = ?, rendimento = ?, categoria_id = ?, imagem = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssissi", $titulo, $descricao, $modoprep, $rendimento, $categoria_id, $nome_imagem, $receita_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['success'] = "Receita atualizada com sucesso!";
            header("Location: /Story-Bytes-/pages/perfil.php");
            exit();
        } else {
            $errors[] = "Erro ao atualizar receita";
        }
    }
}
?>

<main class="perfil-container">
    <section class="perfil-header">
        <div class="welcome-banner">
            <h1>Editar Receita</h1>
            <p>Editando: <strong><?= htmlspecialchars($receita['titulo']) ?></strong></p>
        </div>
    </section>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="tab-content active">
        <form method="POST" enctype="multipart/form-data" class="receita-form">
            <div class="form-group">
                <label for="titulo">Título da Receita *</label>
                <input type="text" id="titulo" name="titulo" required 
                       value="<?= htmlspecialchars($receita['titulo']) ?>"
                       placeholder="Ex: Bolo de Chocolate Especial">
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoria</label>
                <select id="categoria_id" name="categoria_id">
                    <option value="0">Selecione uma categoria</option>
                    <?php if ($categorias && $categorias->num_rows > 0): ?>
                        <?php while($cat = $categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" 
                                    <?= $receita['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição *</label>
                <textarea id="descricao" name="descricao" required rows="3"
                          placeholder="Descreva sua receita de forma atrativa..."><?= htmlspecialchars($receita['descricao']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="rendimento">Rendimento</label>
                <input type="text" id="rendimento" name="rendimento" 
                       value="<?= htmlspecialchars($receita['rendimento']) ?>"
                       placeholder="Ex: 8 porções, 12 unidades, 1 litro">
            </div>

            <div class="form-group">
                <label for="imagem">Imagem da Receita</label>
                <?php if ($receita['imagem']): ?>
                    <div class="imagem-atual" style="margin-bottom: 10px;">
                        <p><strong>Imagem atual:</strong></p>
                        <img src="../img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                             alt="<?= htmlspecialchars($receita['titulo']) ?>"
                             style="max-width: 200px; height: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                        <p style="font-size: 0.9em; color: #666; margin-top: 5px;">
                            Arquivo: <?= htmlspecialchars($receita['imagem']) ?>
                        </p>
                    </div>
                <?php endif; ?>
                <input type="file" id="imagem" name="imagem" accept="image/*">
                <small style="color: #666; font-size: 0.9em;">
                    Formatos aceitos: JPG, JPEG, PNG (máx. 5MB)
                    <?php if (!$receita['imagem']): ?>
                        <br><em>Nenhuma imagem foi adicionada a esta receita.</em>
                    <?php endif; ?>
                </small>
            </div>

            <div class="form-group">
                <label for="modoprep">Modo de Preparo *</label>
                <textarea id="modoprep" name="modoprep" required rows="8"
                          placeholder="Descreva passo a passo como preparar sua receita..."><?= htmlspecialchars($receita['modoprep']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Alterações</button>
                <a href="/Story-Bytes-/pages/perfil.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>