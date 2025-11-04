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
                'css/editar-receita.css'
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

// Verificar se o usuário é o dono da receita ou admin
if ($receita['usuario_id'] != $usuario_id && $_SESSION['usuario_tipo'] !== 'admin') {
    $_SESSION['error'] = "Você não tem permissão para editar esta receita.";
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
    $ingredientes = trim($_POST['ingredientes']);
    $modoprep = trim($_POST['modoprep']);
    $rendimento = trim($_POST['rendimento']);
    $tempo_preparo = trim($_POST['tempo_preparo']);
    $categoria_id = (int)$_POST['categoria_id'];
    
    $errors = [];
    $nome_imagem = $receita['imagem']; // Manter imagem atual por padrão
    
    if (empty($titulo)) $errors[] = "Título é obrigatório";
    if (empty($descricao)) $errors[] = "Descrição é obrigatória";
    if (empty($ingredientes)) $errors[] = "Ingredientes são obrigatórios";
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
        $sql_update = "UPDATE receita SET titulo = ?, descricao = ?, ingredientes = ?, modoprep = ?, rendimento = ?, tempo_preparo = ?, categoria_id = ?, imagem = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssisi", $titulo, $descricao, $ingredientes, $modoprep, $rendimento, $tempo_preparo, $categoria_id, $nome_imagem, $receita_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['success'] = "Receita atualizada com sucesso!";
            header("Location: /Story-Bytes-/pages/perfil.php");
            exit();
        } else {
            $errors[] = "Erro ao atualizar receita: " . $stmt_update->error;
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
                <label for="ingredientes">Ingredientes *</label>
                <textarea id="ingredientes" name="ingredientes" required rows="6"
                          placeholder="Liste os ingredientes, um por linha:&#10;- 2 xícaras de farinha&#10;- 3 ovos&#10;- 1 xícara de açúcar"><?= htmlspecialchars($receita['ingredientes']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="rendimento">Rendimento</label>
                <input type="text" id="rendimento" name="rendimento" 
                       value="<?= htmlspecialchars($receita['rendimento']) ?>"
                       placeholder="Ex: 8 porções, 12 unidades, 1 litro">
                <small class="form-hint">Informe a quantidade e unidade (porções, unidades, litros, etc.)</small>
            </div>

            <div class="form-group">
                <label for="tempo_preparo">Tempo de Preparo</label>
                <input type="text" id="tempo_preparo" name="tempo_preparo" 
                       value="<?= htmlspecialchars($receita['tempo_preparo']) ?>"
                       placeholder="Ex: 30 minutos, 1 hora, 2h30min">
            </div>

            <div class="form-group">
                <label for="imagem">Imagem da Receita</label>
                <?php if ($receita['imagem']): ?>
                    <div class="imagem-atual">
                        <p class="titulo-imagem-atual"><strong>Imagem atual:</strong></p>
                        <img src="/Story-Bytes-/img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                             alt="<?= htmlspecialchars($receita['titulo']) ?>"
                             class="preview-imagem">
                        <p class="info-arquivo">
                            <strong>Arquivo:</strong> <?= htmlspecialchars($receita['imagem']) ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="input-arquivo-container">
                    <input type="file" id="imagem" name="imagem" accept="image/*" 
                           class="input-arquivo">
                </div>
                <small class="info-upload">
                    <strong>Formatos aceitos:</strong> JPG, JPEG, PNG (máximo 5MB)<br>
                    <?php if (!$receita['imagem']): ?>
                        <em class="aviso-sem-imagem">⚠️ Esta receita não possui imagem. Adicione uma para melhorar a apresentação!</em>
                    <?php else: ?>
                        <em class="aviso-com-imagem">✅ Selecione um arquivo para substituir a imagem atual</em>
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

<script>
// Feedback visual para seleção de nova imagem
document.getElementById('imagem').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const imagemAtual = document.querySelector('.imagem-atual');
    
    if (file) {
        // Verificar tamanho do arquivo
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('Arquivo muito grande! O tamanho máximo é 5MB.');
            e.target.value = '';
            return;
        }
        
        // Verificar tipo do arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Formato não permitido! Use apenas JPG, JPEG ou PNG.');
            e.target.value = '';
            return;
        }
        
        // Criar preview da nova imagem
        const reader = new FileReader();
        reader.onload = function(e) {
            // Criar ou atualizar preview
            let preview = document.getElementById('preview-nova-imagem');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'preview-nova-imagem';
                document.querySelector('input[type="file"]').parentNode.appendChild(preview);
            }
            
            preview.innerHTML = `
                <p class="titulo-preview">
                    <strong>Nova imagem selecionada:</strong>
                </p>
                <img src="${e.target.result}" class="imagem-preview">
                <p class="info-preview">
                    <strong>Arquivo:</strong> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                </p>
                <p class="aviso-preview">
                    <em>Esta imagem substituirá a atual quando você salvar as alterações.</em>
                </p>
            `;
            
            // Destacar que a imagem atual será substituída
            if (imagemAtual) {
                imagemAtual.classList.add('sera-substituida');
                if (!document.getElementById('aviso-substituicao')) {
                    const aviso = document.createElement('p');
                    aviso.id = 'aviso-substituicao';
                    aviso.innerHTML = '⚠️ Esta imagem será substituída';
                    imagemAtual.appendChild(aviso);
                }
            }
        };
        reader.readAsDataURL(file);
    } else {
        // Remover preview se arquivo foi removido
        const preview = document.getElementById('preview-nova-imagem');
        if (preview) preview.remove();
        
        // Restaurar aparência da imagem atual
        if (imagemAtual) {
            imagemAtual.classList.remove('sera-substituida');
            const aviso = document.getElementById('aviso-substituicao');
            if (aviso) aviso.remove();
        }
    }
});
</script>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>