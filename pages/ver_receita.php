<?php
$ACTIVE_PAGE = 'receita';
$PAGE_TITLE  = 'StoryBites — Visualizar Receita';
$PAGE_DESC   = 'Visualize os detalhes completos da receita.';
$PAGE_STYLES = [
    'css/card-receitas.css',
    'css/buscar.css'
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

// Verificar se foi passado um ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: buscar.php');
    exit;
}

$receita_id = (int)$_GET['id'];

// Buscar a receita específica
$sql = "SELECT r.*, c.nome as categoria_nome, u.nome as autor_nome 
        FROM receita r 
        LEFT JOIN categoria c ON r.categoria_id = c.id 
        LEFT JOIN usuario u ON r.usuario_id = u.id 
        WHERE r.id = ? AND r.status_aprovacao = 'aprovada'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receita_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: buscar.php');
    exit;
}

$receita = $result->fetch_assoc();
$PAGE_TITLE = 'StoryBites — ' . $receita['titulo'];
?>

<main class="buscar-main">
    <div class="container">
        <!-- Botão de voltar -->
        <div class="voltar-busca">
            <a href="buscar.php" class="btn btn-secondary">
                ← Voltar à Busca
            </a>
        </div>

        <!-- Card da receita completa -->
        <article class="receita-completa">
            <header class="receita-header">
                <h1><?= htmlspecialchars($receita['titulo']) ?></h1>
                <div class="receita-meta-info">
                    <span class="receita-categoria"><?= htmlspecialchars($receita['categoria_nome']) ?></span>
                    <span class="receita-autor">Por: <?= htmlspecialchars($receita['autor_nome']) ?></span>
                </div>
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
                        <span class="detalhe-valor"><?= htmlspecialchars($receita['rendimento'] ?: 'Não informado') ?></span>
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

<style>
/* Estilos específicos para a página de receita completa */
main.buscar-main .voltar-busca {
    margin-bottom: 30px;
}

main.buscar-main .receita-completa {
    background: var(--cor-branca);
    border-radius: 10px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

main.buscar-main .receita-header {
    background: linear-gradient(135deg, #ff7043, #ff8a65);
    color: white;
    padding: 40px 30px;
    text-align: center;
}

main.buscar-main .receita-header h1 {
    margin: 0 0 15px 0;
    font-size: 2.5rem;
    font-family: var(--fonte-titulo);
    font-weight: 600;
}

main.buscar-main .receita-meta-info {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

main.buscar-main .receita-meta-info .receita-categoria {
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

main.buscar-main .receita-meta-info .receita-autor {
    font-style: italic;
    opacity: 0.9;
}

main.buscar-main .receita-content {
    padding: 40px 30px;
}

main.buscar-main .receita-section {
    margin-bottom: 40px;
}

main.buscar-main .receita-section h2 {
    color: #ff7043;
    font-size: 1.8rem;
    font-family: var(--fonte-titulo);
    margin-bottom: 20px;
    border-bottom: 2px solid #ff7043;
    padding-bottom: 10px;
}

main.buscar-main .receita-descricao-completa {
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--cor-secundaria);
    text-align: justify;
}

main.buscar-main .receita-detalhes-principais {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
    padding: 20px;
    background: var(--cor-fundo);
    border-radius: 10px;
}

main.buscar-main .receita-detalhes-principais .detalhe-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: var(--cor-branca);
    border-radius: 8px;
    border-left: 4px solid #ff7043;
}

main.buscar-main .receita-detalhes-principais .detalhe-label {
    font-weight: 600;
    color: var(--cor-secundaria);
}

main.buscar-main .receita-detalhes-principais .detalhe-valor {
    font-weight: 700;
    color: #ff7043;
    background: rgba(255, 112, 67, 0.1);
    padding: 5px 10px;
    border-radius: 5px;
}

main.buscar-main .ingredientes-completos ul {
    list-style: none;
    padding: 0;
}

main.buscar-main .ingredientes-completos li {
    padding: 10px 0 10px 25px;
    position: relative;
    border-bottom: 1px solid #f0f0f0;
    font-size: 1rem;
    line-height: 1.5;
}

main.buscar-main .ingredientes-completos li:before {
    content: "•";
    color: #ff7043;
    position: absolute;
    left: 0;
    font-weight: bold;
    font-size: 1.2rem;
}

main.buscar-main .modo-preparo-completo {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--cor-secundaria);
    text-align: justify;
    padding: 20px;
    background: var(--cor-fundo);
    border-radius: 10px;
    border-left: 4px solid #ff7043;
}

/* Responsividade */
@media (max-width: 768px) {
    main.buscar-main .receita-header {
        padding: 30px 20px;
    }
    
    main.buscar-main .receita-header h1 {
        font-size: 2rem;
    }
    
    main.buscar-main .receita-content {
        padding: 30px 20px;
    }
    
    main.buscar-main .receita-detalhes-principais {
        grid-template-columns: 1fr;
    }
}
</style>

<?php 
require_once APP_ROOT . '/partials/_footer.php'; 
$conn->close();
?>