<?php
$ACTIVE_PAGE = 'receita';
$PAGE_TITLE  = 'StoryBites — Visualizar Receita';
$PAGE_DESC   = 'Visualize os detalhes completos da receita.';
$PAGE_STYLES = [
    'css/card-receitas.css',
    'css/buscar.css',
    'css/ver-receita.css'
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

// Função para formatar rendimento
function formatarRendimento($rendimento) {
    if (!$rendimento) {
        return 'Não informado';
    }
    
    // Se já tem formatação completa (contém letras), retorna como está
    if (preg_match('/[a-zA-Z]/', $rendimento)) {
        return $rendimento;
    }
    
    // Se é apenas número, adiciona formatação padrão
    $numero = intval($rendimento);
    if ($numero > 0) {
        if ($numero === 1) {
            return $numero . ' porção';
        } else {
            return $numero . ' porções';
        }
    }
    
    // Se não conseguir processar, retorna como está
    return $rendimento;
}

// Verificar se foi passado um ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Debug: mostrar erro se ID não foi passado
    echo "<div class='error-message'>";
    echo "<h3>Erro: ID da receita não informado</h3>";
    echo "<p>ID recebido: " . (isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'não informado') . "</p>";
    echo "<p><a href='./index.php'>Voltar ao Início</a></p>";
    echo "</div>";
    exit;
}

$receita_id = (int)$_GET['id'];

// Buscar a receita específica
$sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id 
        LEFT JOIN usuario u ON r.usuario_id = u.id 
        WHERE r.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receita_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Debug: mostrar se receita não foi encontrada
    echo "<div class='warning-message'>";
    echo "<h3>Receita não encontrada</h3>";
    echo "<p>ID buscado: " . $receita_id . "</p>";
    echo "<p>Verifique se a receita existe ou se o ID está correto.</p>";
    echo "<p><a href='./index.php'>← Voltar ao Início</a></p>";
    echo "</div>";
    exit;
}

$receita = $result->fetch_assoc();
$PAGE_TITLE = 'StoryBites — ' . $receita['titulo'];
?>

<main class="buscar-main">
    <div class="container">
        <!-- Botão de voltar -->
        <div class="voltar-busca">
            <a href="/Story-Bytes-/index.php" class="btn btn-secondary">
                Voltar ao Início
            </a>
            </a>
        </div>

        <!-- Card da receita completa -->
        <article class="receita-completa">
            <header class="receita-header">
                <?php if ($receita['imagem'] && file_exists("../img/receitas/" . $receita['imagem'])): ?>
                    <div class="receita-header-with-image">
                        <div class="receita-imagem-header">
                            <img src="/Story-Bytes-/img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                                 alt="<?= htmlspecialchars($receita['titulo']) ?>"
                                 class="receita-imagem-styled">
                        </div>
                        <div class="receita-info-header">
                            <h1><?= htmlspecialchars($receita['titulo']) ?></h1>
                            <div class="receita-meta-info">
                                <span class="receita-categoria"><?= htmlspecialchars($receita['categoria_nome']) ?></span>
                                <span class="receita-autor">Por: <?= htmlspecialchars($receita['autor_nome']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <h1><?= htmlspecialchars($receita['titulo']) ?></h1>
                    <div class="receita-meta-info">
                        <span class="receita-categoria"><?= htmlspecialchars($receita['categoria_nome']) ?></span>
                        <span class="receita-autor">Por: <?= htmlspecialchars($receita['autor_nome']) ?></span>
                    </div>
                <?php endif; ?>
            </header>

            <div class="receita-content">
                <!-- Descrição -->
                <section class="receita-section">
                    <h2>Descrição</h2>
                    <p class="receita-descricao-completa"><?= nl2br(htmlspecialchars($receita['descricao'])) ?></p>
                </section>

                <!-- Detalhes rápidos -->
                <section class="receita-detalhes-principais">
                    <div class="detalhe-item">
                        <span class="detalhe-label">Rendimento:</span>
                        <span class="detalhe-valor"><?= htmlspecialchars(formatarRendimento($receita['rendimento'])) ?></span>
                    </div>
                    <div class="detalhe-item">
                        <span class="detalhe-label">Tempo de Preparo:</span>
                        <span class="detalhe-valor"><?= htmlspecialchars($receita['tempo_preparo'] ?: 'Não informado') ?></span>
                    </div>
                </section>

                <!-- Ingredientes -->
                <section class="receita-section">
                    <h2>Ingredientes</h2>
                    <div class="ingredientes-completos">
                        <?php
                        $ingredientes = explode("\n", $receita['ingredientes']);
                        ?>
                        <ul>
                            <?php foreach($ingredientes as $ingrediente): ?>
                                <?php if (trim($ingrediente)): ?>
                                    <li><?= htmlspecialchars(trim($ingrediente)) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>

                <!-- Modo de preparo -->
                <section class="receita-section">
                    <h2>Modo de Preparo</h2>
                    <div class="modo-preparo-completo">
                        <?= nl2br(htmlspecialchars($receita['modoprep'])) ?>
                    </div>
                </section>
            </div>
        </article>
    </div>
</main>

<?php 
require_once APP_ROOT . '/partials/_footer.php'; 
$conn->close();
?>