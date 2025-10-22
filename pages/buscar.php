<?php
$ACTIVE_PAGE = 'buscar';
$PAGE_TITLE  = 'StoryBites — Buscar Receitas';
$PAGE_DESC   = 'Encontre as melhores receitas do nosso site.';
$PAGE_STYLES = [
                'css/card-receitas.css',
                'css/buscar.css'
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

// Obter o termo de busca
$termo_busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria_filtro = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

// Buscar categorias para o filtro
$sql_categorias = "SELECT * FROM categoria ORDER BY nome";
$categorias = $conn->query($sql_categorias);

// Inicializar variáveis
$receitas = null;
$total_resultados = 0;
$tem_busca = !empty($termo_busca) || $categoria_filtro > 0;

if ($tem_busca) {
    // Construir query de busca
    $sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
            FROM receita r 
            LEFT JOIN categoria c ON r.categoria_id = c.id 
            LEFT JOIN usuario u ON r.usuario_id = u.id 
            WHERE r.status_aprovacao = 'aprovada'";
    
    $params = [];
    $types = '';
    
    // Adicionar filtro de texto
    if (!empty($termo_busca)) {
        $sql .= " AND (r.titulo LIKE ? OR r.descricao LIKE ? OR r.ingredientes LIKE ?)";
        $termo_like = "%{$termo_busca}%";
        $params[] = $termo_like;
        $params[] = $termo_like;
        $params[] = $termo_like;
        $types .= 'sss';
    }
    
    // Adicionar filtro de categoria
    if ($categoria_filtro > 0) {
        $sql .= " AND r.categoria_id = ?";
        $params[] = $categoria_filtro;
        $types .= 'i';
    }
    
    $sql .= " ORDER BY r.datacriacao DESC LIMIT 50";
    
    // Executar busca
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $receitas = $stmt->get_result();
        $total_resultados = $receitas->num_rows;
    } else {
        // Busca sem filtros - mostrar todas as receitas aprovadas
        $receitas = $conn->query($sql);
        $total_resultados = $receitas->num_rows;
    }
}
?>

<main class="buscar-main">
    
    <!-- Título principal seguindo padrão do site -->
    <section class="apresentacao">
        <h1 class="buscar-titulo">Buscar Receitas</h1>
    </section>
    
    <!-- Formulário de Busca -->
    <section class="buscar-form">
        <form method="GET" action="buscar.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="termo">O que você está procurando?</label>
                    <input type="text" id="termo" name="busca" class="form-control"
                           value="<?= htmlspecialchars($termo_busca) ?>" 
                           placeholder="Digite o nome da receita, ingrediente ou descrição...">
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <option value="0">Todas as categorias</option>
                        <?php if ($categorias && $categorias->num_rows > 0): ?>
                            <?php while($cat = $categorias->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>" 
                                        <?= $categoria_filtro == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="buscar.php" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </section>

    <!-- Resultados da Busca -->
    <?php if ($tem_busca): ?>
        <section class="resultados-container">
            <div class="resultados-info">
                <h3>Resultados da Busca</h3>
                <p>
                    <?php if ($total_resultados > 0): ?>
                        Encontradas <strong><?= $total_resultados ?></strong> receita(s)
                        <?php if (!empty($termo_busca)): ?>
                            para "<strong><?= htmlspecialchars($termo_busca) ?></strong>"
                        <?php endif; ?>
                        <?php if ($categoria_filtro > 0): ?>
                            na categoria selecionada
                        <?php endif; ?>
                    <?php else: ?>
                        Nenhuma receita encontrada
                        <?php if (!empty($termo_busca)): ?>
                            para "<strong><?= htmlspecialchars($termo_busca) ?></strong>"
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($total_resultados > 0): ?>
                <div class="receitas-grid">
                    <?php while($receita = $receitas->fetch_assoc()): ?>
                        <article class="receita-card" onclick="abrirModal(<?= $receita['id'] ?>)">
                            <div class="receita-card-header">
                                <h4><?= htmlspecialchars($receita['titulo']) ?></h4>
                                <span class="receita-categoria"><?= htmlspecialchars($receita['categoria_nome'] ?? 'Sem categoria') ?></span>
                            </div>
                            
                            <div class="receita-card-body">
                                <p class="receita-descricao"><?= htmlspecialchars(substr($receita['descricao'], 0, 120)) ?>...</p>
                                
                                <div class="receita-meta">
                                    <span class="receita-tempo"><?= htmlspecialchars($receita['tempo_preparo'] ?? 'Não informado') ?></span>
                                    <?php if ($receita['autor_nome']): ?>
                                        <span class="receita-autor">Por: <?= htmlspecialchars($receita['autor_nome']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                                    <?php 
                                    $ingredientes = explode("\n", $receita['ingredientes']);
                                    $primeiros_ingredientes = array_slice($ingredientes, 0, 3);
                                    ?>
                                    <ul>
                                        <?php foreach($primeiros_ingredientes as $ingrediente): ?>
                                            <?php if (trim($ingrediente)): ?>
                                                <li><?= htmlspecialchars(trim($ingrediente)) ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if (count($ingredientes) > 3): ?>
                                            <li><em>... e mais ingredientes</em></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                
                                <div class="acoes-receita">
                                    <button class="btn-ver-receita" onclick="verReceitaCompleta(<?= $receita['id'] ?>)">
                                        👁️ Ver Receita Completa
                                    </button>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="sem-resultados">
                    <h3>Ops! Nenhuma receita encontrada</h3>
                    <p>Que tal tentar:</p>
                    <ul>
                        <li>Verificar a ortografia dos termos</li>
                        <li>Usar palavras mais gerais</li>
                        <li>Tentar ingredientes específicos como "frango", "chocolate", "massa"</li>
                        <li>Explorar categorias diferentes</li>
                    </ul>
                </div>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <!-- Página inicial da busca -->
        <section class="categorias-populares">
            <h3>Explore por categoria:</h3>
            <div class="categorias-links">
                <?php 
                $categorias->data_seek(0); // Reset do ponteiro
                while($cat = $categorias->fetch_assoc()): 
                ?>
                    <a href="?categoria=<?= $cat['id'] ?>" class="categoria-link">
                        <?= htmlspecialchars($cat['nome']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
            
            <div class="receitas-destaque">
                <h3>Receitas em Destaque:</h3>
                <?php
                // Buscar algumas receitas aleatórias para destaque
                $sql_destaque = "SELECT r.*, c.nome as categoria_nome 
                                FROM receita r 
                                LEFT JOIN categoria c ON r.categoria_id = c.id 
                                WHERE r.status_aprovacao = 'aprovada' 
                                ORDER BY RAND() 
                                LIMIT 6";
                $receitas_destaque = $conn->query($sql_destaque);
                ?>
                
                <?php if ($receitas_destaque && $receitas_destaque->num_rows > 0): ?>
                    <div class="receitas-grid">
                        <?php while($receita = $receitas_destaque->fetch_assoc()): ?>
                            <div class="receita-card" onclick="abrirModal(<?= $receita['id'] ?>)">
                                <div class="receita-card-header">
                                    <h4><?= htmlspecialchars($receita['titulo']) ?></h4>
                                    <span class="receita-categoria"><?= htmlspecialchars($receita['categoria_nome']) ?></span>
                                </div>
                                <div class="receita-card-body">
                                    <p class="receita-descricao"><?= htmlspecialchars(substr($receita['descricao'], 0, 80)) ?>...</p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<!-- Modal para receita completa -->
<div id="modalReceita" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo"></h2>
            <span class="close" onclick="fecharModal()">&times;</span>
        </div>
        <div class="modal-body">
            <h3>Descrição</h3>
            <p id="modalDescricao"></p>
            
            <h3>Ingredientes</h3>
            <div id="modalIngredientes"></div>
            
            <h3>Modo de Preparo</h3>
            <div id="modalModoPreparo"></div>
            
            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <div>
                    <strong>Rendimento:</strong> <span id="modalRendimento"></span>
                </div>
                <div>
                    <strong>Tempo:</strong> <span id="modalTempo"></span>
                </div>
                <div>
                    <strong>Categoria:</strong> <span id="modalCategoria"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModal(receitaId) {
    fetch('obter_receita_publica.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarModalReceitaBusca(data.receita);
            } else {
                alert('❌ Erro ao carregar receita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao carregar receita');
        });
}

    fetch('obter_receita_publica.php?id=' + receitaId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const receita = data.receita;
                document.getElementById('modalTitulo').textContent = receita.titulo;
                document.getElementById('modalDescricao').textContent = receita.descricao;
                document.getElementById('modalIngredientes').innerHTML = receita.ingredientes.replace(/\n/g, '<br>');
                document.getElementById('modalModoPreparo').innerHTML = receita.modoprep.replace(/\n/g, '<br>');
                document.getElementById('modalRendimento').textContent = receita.rendimento || 'Não informado';
                document.getElementById('modalTempo').textContent = receita.tempo_preparo || 'Não informado';
                document.getElementById('modalCategoria').textContent = receita.categoria_nome || 'Sem categoria';
                
                document.getElementById('modalReceita').style.display = 'block';
            } else {
                alert('Erro ao carregar receita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar receita');
        });
}

function fecharModal() {
    document.getElementById('modalReceita').style.display = 'none';
}

// Fechar modal clicando fora
window.onclick = function(event) {
    const modal = document.getElementById('modalReceita');
    if (event.target === modal) {
        fecharModal();
    }
}
</script>

<?php 
require_once APP_ROOT . '/partials/_footer.php'; 
$conn->close();
?>