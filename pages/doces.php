<?php
$ACTIVE_PAGE = 'doces';
$PAGE_TITLE  = 'StoryBites — Doces';
$PAGE_DESC   = 'Deliciosas sobremesas e doces caseiros para adoçar seu dia';
$PAGE_STYLES = [
                'css/card-receitas.css',
                'css/buscar.css',
                'css/categoria-banner.css'
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

// Primeiro, contar o total de receitas da categoria Doces
$sql_count = "SELECT COUNT(*) as total 
              FROM receita r 
              LEFT JOIN categoria c ON r.categoria_id = c.id 
              WHERE c.nome = 'Doces'";
$result_count = $conn->query($sql_count);
$total_receitas = $result_count ? $result_count->fetch_assoc()['total'] : 0;

// Buscar todas as receitas da categoria Doces
$sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id 
        LEFT JOIN usuario u ON r.usuario_id = u.id 
        WHERE c.nome = 'Doces' 
        ORDER BY r.datacriacao DESC";

$receitas = $conn->query($sql);
?>

<main class="buscar-main">
    <div class="container">
        <!-- Cabeçalho da categoria -->
        <section class="categoria-header">
            <div class="categoria-banner doces">
                <div class="categoria-banner-imagem">
                    <img src="/Story-Bytes-/img/doces.jpg" alt="Doces deliciosos">
                </div>
                <div class="categoria-banner-conteudo">
                    <h1 class="categoria-banner-titulo">Doces</h1>
                    <p class="categoria-banner-descricao">Receitas irresistíveis de doces para adoçar o seu dia</p>
                    <p class="categoria-contador"><?= $total_receitas ?> receita<?= $total_receitas != 1 ? 's' : '' ?></p>
                </div>
            </div>
        </section>

        <!-- Botão de voltar -->
        <div class="voltar-busca">
            <a href="/Story-Bytes-/index.php" class="btn btn-secondary">
                Voltar ao Início
            </a>
        </div>

        <!-- Lista de receitas -->
        <?php if ($receitas && $receitas->num_rows > 0): ?>
            <div class="receitas-grid">
                <?php while($receita = $receitas->fetch_assoc()): ?>
                    <article class="receita-card">
                        <div class="receita-card-header">
                            <h3><?= htmlspecialchars($receita['titulo']) ?></h3>
                            <div class="receita-meta">
                                <span class="categoria"><?= htmlspecialchars($receita['categoria_nome']) ?></span>
                                <?php if ($receita['autor_nome']): ?>
                                    <span class="autor">Por: <?= htmlspecialchars($receita['autor_nome']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="receita-card-body">
                            <?php if ($receita['imagem'] && file_exists("../img/receitas/" . $receita['imagem'])): ?>
                                <div class="receita-imagem">
                                    <img src="/Story-Bytes-/img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                                         alt="<?= htmlspecialchars($receita['titulo']) ?>">
                                </div>
                            <?php else: ?>
                                <!-- Imagem padrão para doces -->
                                <div class="receita-imagem">
                                    <img src="/Story-Bytes-/img/doces.jpg" 
                                         alt="<?= htmlspecialchars($receita['titulo']) ?>">
                                </div>
                            <?php endif; ?>

                            <div class="receita-descricao">
                                <p><?= htmlspecialchars(substr($receita['descricao'], 0, 150)) ?>...</p>
                            </div>

                            <div class="receita-detalhes">
                                <h5>Ingredientes:</h5>
                                <div class="ingredientes-resumo">
                                    <?= nl2br(htmlspecialchars(substr($receita['ingredientes'], 0, 200))) ?>...
                                </div>

                                <h5>Modo de Preparo:</h5>
                                <p class="modo-preparo-resumo">
                                    <?= htmlspecialchars(substr($receita['modoprep'], 0, 300)) ?>...
                                </p>
                            </div>
                        </div>

                        <div class="acoes-receita">
                            <a href="/Story-Bytes-/pages/ver_receita.php?id=<?= $receita['id'] ?>" class="btn-ver-receita">
                                Ver Receita Completa
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="sem-resultados">
                <h3>Nenhuma receita de doces encontrada</h3>
                <p>Ainda não temos receitas cadastradas nesta categoria.</p>
                <p><a href="/Story-Bytes-/pages/buscar.php">← Voltar à busca</a></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>