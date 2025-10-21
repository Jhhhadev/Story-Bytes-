<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Story-Bytes-/pages/login.php");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Meu Perfil';
$PAGE_DESC   = 'Gerencie suas receitas e envie novas criações para aprovação.';
$PAGE_STYLES = [
                'css/login.css', // Reutilizar estilos base
                'css/perfil.css', // Estilos específicos do perfil
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
include('../backend/conexao.php');

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$usuario_tipo = $_SESSION['usuario_tipo'];

// Buscar receitas do usuário (usando a estrutura atual do banco)
$sql_receitas = "SELECT r.* FROM receita r 
                 WHERE r.titulo LIKE '%usuario_%' 
                 ORDER BY r.datacriacao DESC 
                 LIMIT 10";
$receitas_usuario = $conn->query($sql_receitas);

// Se não houver coluna usuario_id ainda, simulamos resultado vazio
if (!$receitas_usuario) {
    $receitas_usuario = new stdClass();
    $receitas_usuario->num_rows = 0;
}

// Buscar categorias disponíveis
$sql_categorias = "SELECT * FROM categoria ORDER BY nome";
$categorias = $conn->query($sql_categorias);

// Se não houver tabela categoria, criar array padrão
if (!$categorias) {
    $categorias_array = [
        ['id' => 1, 'nome' => 'Doces'],
        ['id' => 2, 'nome' => 'Massas'],
        ['id' => 3, 'nome' => 'Carnes'],
        ['id' => 4, 'nome' => 'Sopas'],
        ['id' => 5, 'nome' => 'Lanches'],
        ['id' => 6, 'nome' => 'Bebidas']
    ];
}
?>

<main class="perfil-container">
    <!-- Cabeçalho do Perfil -->
    <section class="perfil-header">
        <div class="welcome-banner">
            <h1>👤 Meu Perfil</h1>
            <p>Bem-vindo, <strong><?= htmlspecialchars($usuario_nome) ?></strong>!</p>
            <p class="user-type">
                <?= $usuario_tipo === 'admin' ? '👑 Administrador' : '👨‍🍳 Chef Caseiro' ?>
            </p>
        </div>
    </section>

    <!-- Estatísticas do Usuário -->
    <section class="estatisticas">
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= ($receitas_usuario && $receitas_usuario->num_rows) ? $receitas_usuario->num_rows : 0 ?></h3>
                <p>Receitas Criadas</p>
            </div>
            <div class="stat-card">
                <h3>0</h3>
                <p>Receitas Aprovadas</p>
            </div>
            <div class="stat-card">
                <h3>0</h3>
                <p>Pendentes</p>
            </div>
        </div>
    </section>

    <!-- Abas de Navegação -->
    <section class="perfil-tabs">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="criar">➕ Criar Receita</button>
            <button class="tab-btn" data-tab="minhas">📋 Minhas Receitas</button>
            <button class="tab-btn" data-tab="dados">⚙️ Meus Dados</button>
        </div>

        <!-- Aba: Criar Receita -->
        <div class="tab-content active" id="tab-criar">
            <div class="form-container">
                <h2>✨ Criar Nova Receita</h2>
                <form action="processa_receita.php" method="POST" enctype="multipart/form-data" class="receita-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="titulo">📝 Título da Receita</label>
                            <input type="text" id="titulo" name="titulo" required 
                                   placeholder="Ex: Bolo de Chocolate da Vovó">
                        </div>
                        
                        <div class="form-group">
                            <label for="categoria">🏷️ Categoria</label>
                            <select id="categoria" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php 
                                if ($categorias && $categorias->num_rows > 0): 
                                    while($categoria = $categorias->fetch_assoc()): 
                                ?>
                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                                <?php 
                                    endwhile; 
                                else:
                                    // Usar categorias padrão se não houver tabela
                                    foreach($categorias_array as $categoria):
                                ?>
                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descricao">📖 Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" required
                                  placeholder="Conte a história desta receita..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ingredientes">🥄 Ingredientes</label>
                        <textarea id="ingredientes" name="ingredientes" rows="6" required
                                  placeholder="Liste os ingredientes, um por linha:&#10;- 2 xícaras de farinha&#10;- 3 ovos&#10;- 1 xícara de açúcar"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="modo_preparo">👩‍🍳 Modo de Preparo</label>
                        <textarea id="modo_preparo" name="modoprep" rows="8" required
                                  placeholder="Descreva o passo a passo:&#10;1. Pré-aqueça o forno...&#10;2. Misture os ingredientes secos..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rendimento">🍽️ Rendimento</label>
                            <input type="text" id="rendimento" name="rendimento" 
                                   placeholder="Ex: 8 porções, 12 unidades">
                        </div>

                        <div class="form-group">
                            <label for="tempo_preparo">⏱️ Tempo de Preparo</label>
                            <input type="text" id="tempo_preparo" name="tempo_preparo" 
                                   placeholder="Ex: 45 minutos, 2 horas">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="imagem">📸 Imagem da Receita</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                        <small>Formato: JPG, PNG. Tamanho máximo: 2MB</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">🚀 Enviar para Aprovação</button>
                        <button type="reset" class="btn-secondary">🔄 Limpar Formulário</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Aba: Minhas Receitas -->
        <div class="tab-content" id="tab-minhas">
            <h2>📋 Minhas Receitas</h2>
            
            <?php if ($receitas_usuario && $receitas_usuario->num_rows > 0): ?>
                <div class="receitas-grid">
                    <?php 
                    $receitas_usuario->data_seek(0); // Resetar o ponteiro
                    while($receita = $receitas_usuario->fetch_assoc()): 
                    ?>
                        <div class="receita-card">
                            <div class="card-header">
                                <h3><?= htmlspecialchars($receita['titulo']) ?></h3>
                                <span class="status-badge status-pendente">⏳ Pendente</span>
                            </div>
                            <div class="card-body">
                                <p><strong>Categoria:</strong> Geral</p>
                                <p><strong>Criada em:</strong> <?= date('d/m/Y', strtotime($receita['datacriacao'])) ?></p>
                                <p class="descricao"><?= htmlspecialchars(substr($receita['descricao'], 0, 100)) ?>...</p>
                            </div>
                            <div class="card-actions">
                                <button class="btn-small btn-edit">✏️ Editar</button>
                                <button class="btn-small btn-delete">🗑️ Excluir</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>🍽️ Você ainda não criou nenhuma receita.</p>
                    <p>Que tal compartilhar sua primeira criação culinária?</p>
                    <button class="btn-primary" onclick="switchTab('criar')">➕ Criar Primeira Receita</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Aba: Meus Dados -->
        <div class="tab-content" id="tab-dados">
            <h2>⚙️ Meus Dados</h2>
            <div class="dados-container">
                <div class="info-group">
                    <label>👤 Nome Completo:</label>
                    <p><?= htmlspecialchars($usuario_nome) ?></p>
                </div>
                
                <div class="info-group">
                    <label>📧 E-mail:</label>
                    <p><?= htmlspecialchars($_SESSION['usuario_email'] ?? 'Não disponível') ?></p>
                </div>
                
                <div class="info-group">
                    <label>👨‍🍳 Tipo de Usuário:</label>
                    <p><?= $usuario_tipo === 'admin' ? 'Administrador' : 'Chef Caseiro' ?></p>
                </div>
                
                <div class="actions">
                    <button class="btn-primary">✏️ Editar Dados</button>
                    <button class="btn-secondary">🔐 Alterar Senha</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// JavaScript para as abas
function switchTab(tabName) {
    // Remover classe active de todos os botões e conteúdos
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Adicionar classe active ao botão e conteúdo selecionados
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    document.querySelector(`#tab-${tabName}`).classList.add('active');
}

// Event listeners para os botões das abas
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tabName = btn.getAttribute('data-tab');
        switchTab(tabName);
    });
});
</script>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>