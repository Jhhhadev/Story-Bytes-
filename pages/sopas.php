<?php
$ACTIVE_PAGE = 'sopas';
$PAGE_TITLE  = 'StoryBites — Sopas';
$PAGE_DESC   = 'Sopas reconfortantes e nutritivas para aquecer o coração';
$PAGE_STYLES = [
                'css/card-receitas.css',
                'css/buscar.css'
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

// Buscar todas as receitas da categoria Sopas
$sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id 
        LEFT JOIN usuario u ON r.usuario_id = u.id 
        WHERE c.nome = 'Sopas' 
        ORDER BY r.datacriacao DESC";

$receitas = $conn->query($sql);
$total_receitas = $receitas ? $receitas->num_rows : 0;
?>

<main class="buscar-main">
    <div class="container">
        <!-- Cabeçalho da categoria -->
        <section class="categoria-header">
            <div class="categoria-banner" style="display: flex !important; align-items: center !important; background: linear-gradient(135deg, #ff7043 0%, #ff8a65 100%) !important; min-height: 220px !important; border-radius: 15px !important; overflow: hidden !important; color: white !important; margin-bottom: 30px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;">
                <div style="flex: 1 !important; max-width: 320px !important; height: 220px !important; overflow: hidden !important; margin-left: 20px !important;">
                    <img src="http://localhost/Story-Bytes-/img/sopas.png" alt="Sopas quentinhas" 
                         style="width: 100% !important; height: 100% !important; object-fit: cover !important; display: block !important; opacity: 1 !important; visibility: visible !important; border-radius: 15px !important;">
                </div>
                <div style="flex: 2 !important; padding: 40px !important;">
                    <h1 style="font-size: 2.8rem !important; margin: 0 0 20px 0 !important; font-weight: 700 !important; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4) !important; color: white !important;">Sopas</h1>
                    <p style="font-size: 1.2rem !important; margin: 0 0 15px 0 !important; line-height: 1.6 !important; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3) !important; color: white !important;">Receitas de sopas reconfortantes e nutritivas</p>
                    <p style="font-size: 1.1rem !important; font-weight: 600 !important; background: rgba(255, 255, 255, 0.25) !important; padding: 12px 20px !important; border-radius: 25px !important; display: inline-block !important; margin-top: 15px !important; backdrop-filter: blur(10px) !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; color: white !important;"><?= $total_receitas ?> receita<?= $total_receitas != 1 ? 's' : '' ?> encontrada<?= $total_receitas != 1 ? 's' : '' ?></p>
                </div>
            </div>
        </section>

        <!-- Botão de voltar -->
        <div class="voltar-busca">
            <a href="/Story-Bytes-/pages/buscar.php" class="btn btn-secondary">
                ← Voltar à Busca
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
                                    <img src="../img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                                         alt="<?= htmlspecialchars($receita['titulo']) ?>">
                                </div>
                            <?php else: ?>
                                <!-- Imagem padrão para sopas -->
                                <div class="receita-imagem">
                                    <img src="../img/sopas.png" 
                                         alt="<?= htmlspecialchars($receita['titulo']) ?>"
                                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
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
                <h3>Nenhuma receita de sopas encontrada</h3>
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