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

// Buscar dados completos do usuário
$sql_usuario = "SELECT * FROM usuario WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$dados_usuario = $stmt_usuario->get_result()->fetch_assoc();

// Sincronizar sessão com dados do banco (garantir dados atualizados)
if ($dados_usuario) {
    $_SESSION['usuario_nome'] = $dados_usuario['nome'];
    $_SESSION['usuario_email'] = $dados_usuario['email'];
    $_SESSION['usuario_tipo'] = $dados_usuario['tipo_usuario'];
    
    // Atualizar variáveis locais
    $usuario_nome = $dados_usuario['nome'];
    $usuario_email = $dados_usuario['email'];
    $usuario_tipo = $dados_usuario['tipo_usuario'];
}

// Buscar receitas do usuário (como não há usuario_id, mostrar todas as receitas)
$sql_receitas = "SELECT r.*, c.nome as categoria_nome 
                 FROM receita r 
                 LEFT JOIN categoria c ON r.categoria_id = c.id
                 ORDER BY r.datacriacao DESC";
$receitas_usuario = $conn->query($sql_receitas);

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
                <form action="/Story-Bytes-/pages/processa_receita.php" method="POST" enctype="multipart/form-data" class="receita-form">
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
                        <button type="submit" name="acao" value="salvar" class="btn-secondary">💾 Salvar como Rascunho</button>
                        <button type="submit" name="acao" value="aprovar" class="btn-primary">🚀 Enviar para Aprovação</button>
                        <button type="reset" class="btn-outline">🔄 Limpar Formulário</button>
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
                                <?php
                                $status = $receita['status_aprovacao'];
                                $status_class = '';
                                $status_text = '';
                                $status_icon = '';
                                
                                switch($status) {
                                    case 'rascunho':
                                        $status_class = 'status-rascunho';
                                        $status_text = '💾 Rascunho';
                                        break;
                                    case 'pendente':
                                        $status_class = 'status-pendente';
                                        $status_text = '⏳ Pendente';
                                        break;
                                    case 'aprovada':
                                        $status_class = 'status-aprovada';
                                        $status_text = '✅ Aprovada';
                                        break;
                                    case 'rejeitada':
                                        $status_class = 'status-rejeitada';
                                        $status_text = '❌ Rejeitada';
                                        break;
                                    default:
                                        $status_class = 'status-pendente';
                                        $status_text = '⏳ Pendente';
                                }
                                ?>
                                <span class="status-badge <?= $status_class ?>"><?= $status_text ?></span>
                            </div>
                            <div class="card-body">
                                <p><strong>Categoria:</strong> <?= htmlspecialchars($receita['categoria_nome'] ?? 'Sem categoria') ?></p>
                                <p><strong>Criada em:</strong> <?= date('d/m/Y', strtotime($receita['datacriacao'])) ?></p>
                                <p class="descricao"><?= htmlspecialchars(substr($receita['descricao'], 0, 100)) ?>...</p>
                                
                                <?php if ($receita['imagem']): ?>
                                    <div class="receita-imagem">
                                        <img src="../img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                                             alt="<?= htmlspecialchars($receita['titulo']) ?>" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; margin-top: 10px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-actions">
                                <button class="btn-small btn-view" onclick="verReceita(<?= $receita['id'] ?>)">👁️ Ver</button>
                                <button class="btn-small btn-edit" onclick="editarReceita(<?= $receita['id'] ?>)">✏️ Editar</button>
                                <?php if ($status === 'rascunho'): ?>
                                    <button class="btn-small btn-send" onclick="enviarAprovacao(<?= $receita['id'] ?>)">🚀 Enviar</button>
                                <?php endif; ?>
                                <button class="btn-small btn-delete" onclick="excluirReceita(<?= $receita['id'] ?>)">🗑️ Excluir</button>
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
            
            <!-- Botões de alternância -->
            <div class="dados-toggle">
                <button class="toggle-btn active" id="btn-visualizar" onclick="toggleDadosMode('visualizar')">👁️ Visualizar</button>
                <button class="toggle-btn" id="btn-editar" onclick="toggleDadosMode('editar')">✏️ Editar</button>
            </div>
            
            <!-- Modo Visualização -->
            <div class="dados-container" id="dados-visualizar">
                <div class="info-group">
                    <label>👤 Nome Completo:</label>
                    <p><?= htmlspecialchars($dados_usuario['nome']) ?></p>
                </div>
                
                <div class="info-group">
                    <label>📧 E-mail:</label>
                    <p><?= htmlspecialchars($dados_usuario['email']) ?></p>
                </div>
                
                <div class="info-group">
                    <label>📅 Data de Cadastro:</label>
                    <p><?= date('d/m/Y', strtotime($dados_usuario['dataCadastro'])) ?></p>
                </div>
                
                <div class="info-group">
                    <label>👨‍🍳 Tipo de Usuário:</label>
                    <p><?= $dados_usuario['tipo_usuario'] === 'admin' ? 'Administrador' : 'Chef Caseiro' ?></p>
                </div>
                
                <div class="actions">
                    <button class="btn-primary" onclick="toggleDadosMode('editar')">✏️ Editar Dados</button>
                    <button class="btn-secondary" onclick="toggleDadosMode('senha')">🔐 Alterar Senha</button>
                    <button class="btn-outline" onclick="atualizarDados()" title="Recarregar dados do banco">🔄 Atualizar</button>
                </div>
            </div>
            
            <!-- Modo Edição -->
            <div class="dados-container" id="dados-editar" style="display: none;">
                <form action="atualizar_dados.php" method="POST" class="dados-form">
                    <div class="form-group">
                        <label for="edit-nome">👤 Nome Completo</label>
                        <input type="text" id="edit-nome" name="nome" value="<?= htmlspecialchars($dados_usuario['nome']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-email">📧 E-mail</label>
                        <input type="email" id="edit-email" name="email" value="<?= htmlspecialchars($dados_usuario['email']) ?>" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">💾 Salvar Alterações</button>
                        <button type="button" class="btn-secondary" onclick="toggleDadosMode('visualizar')">❌ Cancelar</button>
                    </div>
                </form>
            </div>
            
            <!-- Modo Alterar Senha -->
            <div class="dados-container" id="dados-senha" style="display: none;">
                <form action="atualizar_senha.php" method="POST" class="dados-form">
                    <div class="form-group">
                        <label for="senha-atual">🔒 Senha Atual</label>
                        <input type="password" id="senha-atual" name="senha_atual" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nova-senha">🔐 Nova Senha</label>
                        <input type="password" id="nova-senha" name="nova_senha" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar-senha">🔐 Confirmar Nova Senha</label>
                        <input type="password" id="confirmar-senha" name="confirmar_senha" required minlength="6">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">🔐 Alterar Senha</button>
                        <button type="button" class="btn-secondary" onclick="toggleDadosMode('visualizar')">❌ Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal para visualizar receita completa -->
<div id="modalReceita" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-titulo"></h2>
            <span class="modal-close" onclick="fecharModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modal-image-container">
                <img id="modal-imagem" alt="Imagem da receita">
            </div>
            
            <div class="modal-info">
                <div class="info-section">
                    <h3>📋 Descrição</h3>
                    <p id="modal-descricao"></p>
                </div>
                
                <div class="info-section">
                    <h3>🥄 Ingredientes</h3>
                    <div id="modal-ingredientes"></div>
                </div>
                
                <div class="info-section">
                    <h3>👩‍🍳 Modo de Preparo</h3>
                    <div id="modal-modo-preparo"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-item">
                        <h4>🍽️ Rendimento</h4>
                        <p id="modal-rendimento"></p>
                    </div>
                    <div class="info-item">
                        <h4>⏱️ Tempo de Preparo</h4>
                        <p id="modal-tempo"></p>
                    </div>
                    <div class="info-item">
                        <h4>📅 Criada em</h4>
                        <p id="modal-data"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

// JavaScript para alternância de modos na aba dados
function toggleDadosMode(modo) {
    // Esconder todos os containers
    document.getElementById('dados-visualizar').style.display = 'none';
    document.getElementById('dados-editar').style.display = 'none';
    document.getElementById('dados-senha').style.display = 'none';
    
    // Remover classe active de todos os botões
    document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
    
    // Mostrar o container selecionado
    if (modo === 'visualizar') {
        document.getElementById('dados-visualizar').style.display = 'block';
        document.getElementById('btn-visualizar').classList.add('active');
    } else if (modo === 'editar') {
        document.getElementById('dados-editar').style.display = 'block';
        document.getElementById('btn-editar').classList.add('active');
    } else if (modo === 'senha') {
        document.getElementById('dados-senha').style.display = 'block';
        // Não há botão específico para senha, ele é ativado pelo botão "Alterar Senha"
    }
}

// Event listeners para os botões das abas
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tabName = btn.getAttribute('data-tab');
        switchTab(tabName);
    });
});

// Validação de confirmação de senha
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="atualizar_senha.php"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const novaSenha = document.getElementById('nova-senha').value;
            const confirmarSenha = document.getElementById('confirmar-senha').value;
            
            if (novaSenha !== confirmarSenha) {
                e.preventDefault();
                alert('❌ As senhas não coincidem! Por favor, verifique.');
                return false;
            }
            
            if (novaSenha.length < 6) {
                e.preventDefault();
                alert('❌ A senha deve ter pelo menos 6 caracteres!');
                return false;
            }
        });
    }
});

// Funções para gerenciamento de receitas
function verReceita(id) {
    // Fazer uma requisição AJAX para buscar os dados completos da receita
    fetch('obter_receita.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarModalReceita(data.receita);
            } else {
                alert('❌ Erro ao carregar receita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao carregar receita');
        });
}

function mostrarModalReceita(receita) {
    const modal = document.getElementById('modalReceita');
    
    document.getElementById('modal-titulo').textContent = receita.titulo;
    document.getElementById('modal-descricao').textContent = receita.descricao;
    document.getElementById('modal-ingredientes').innerHTML = receita.ingredientes.replace(/\n/g, '<br>');
    document.getElementById('modal-modo-preparo').innerHTML = receita.modoprep.replace(/\n/g, '<br>');
    document.getElementById('modal-rendimento').textContent = receita.rendimento || 'Não informado';
    document.getElementById('modal-tempo').textContent = receita.tempo_preparo || 'Não informado';
    document.getElementById('modal-data').textContent = new Date(receita.datacriacao).toLocaleDateString('pt-BR');
    
    const modalImagem = document.getElementById('modal-imagem');
    if (receita.imagem) {
        modalImagem.src = '../img/receitas/' + receita.imagem;
        modalImagem.style.display = 'block';
    } else {
        modalImagem.style.display = 'none';
    }
    
    modal.style.display = 'block';
}

function fecharModal() {
    document.getElementById('modalReceita').style.display = 'none';
}

function editarReceita(id) {
    // Redirecionar para página de edição
    window.location.href = 'editar_receita.php?id=' + id;
}

function enviarAprovacao(id) {
    if (confirm('📤 Enviar esta receita para aprovação do administrador?')) {
        fetch('alterar_status_receita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id + '&status=pendente'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Receita enviada para aprovação!');
                location.reload();
            } else {
                alert('❌ Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao enviar receita');
        });
    }
}

function excluirReceita(id) {
    if (confirm('🗑️ Tem certeza que deseja excluir esta receita?\n\nEsta ação não pode ser desfeita!')) {
        fetch('excluir_receita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Receita excluída com sucesso!');
                location.reload();
            } else {
                alert('❌ Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('❌ Erro ao excluir receita');
        });
    }
}

// Fechar modal clicando fora dele
window.onclick = function(event) {
    const modal = document.getElementById('modalReceita');
    if (event.target === modal) {
        fecharModal();
    }
}

// Função para atualizar dados em tempo real
function atualizarDados() {
    fetch('sincronizar_sessao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recarregar a página para mostrar dados atualizados
            window.location.reload();
        } else {
            alert('❌ Erro ao atualizar dados: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('❌ Erro ao conectar com o servidor');
    });
}
</script>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>