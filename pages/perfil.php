<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Salvar a URL de destino para redirecionar após login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /Story-Bytes-/pages/login.php?msg=login_required");
    exit();
}

$ACTIVE_PAGE = 'perfil';
$PAGE_TITLE  = 'StoryBites — Meu Perfil';
$PAGE_DESC   = 'Gerencie suas receitas e crie novas delícias culinárias.';
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

// Buscar receitas do usuário logado
$sql_receitas = "SELECT r.*, c.nome as categoria_nome 
                 FROM receita r 
                 LEFT JOIN categoria c ON r.categoria_id = c.id
                 WHERE r.usuario_id = ?
                 ORDER BY r.datacriacao DESC";
$stmt_receitas = $conn->prepare($sql_receitas);
$stmt_receitas->bind_param("i", $usuario_id);
$stmt_receitas->execute();
$receitas_usuario = $stmt_receitas->get_result();

// Calcular estatísticas de receitas
$total_receitas = 0;

if ($receitas_usuario && $receitas_usuario->num_rows > 0) {
    $total_receitas = $receitas_usuario->num_rows;
    
    // Resetar ponteiro para uso posterior
    $receitas_usuario->data_seek(0);
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
            <h1>Meu Perfil</h1>
            <p>Bem-vindo, <strong><?= htmlspecialchars($usuario_nome) ?></strong>!</p>
            <p class="user-type">
                <?= $usuario_tipo === 'admin' ? 'Administrador' : 'Chef Caseiro' ?>
            </p>
            <p class="receitas-count">
                Receitas Criadas: <?= $total_receitas ?>
            </p>
        </div>
    </section>

    <!-- Abas de Navegação -->
    <section class="perfil-tabs">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="criar">Criar Receita</button>
            <button class="tab-btn" data-tab="minhas">Minhas Receitas</button>
            <button class="tab-btn" data-tab="dados">Meus Dados</button>
        </div>

        <!-- Aba: Criar Receita -->
        <div class="tab-content active" id="tab-criar">
            <div class="form-container">
                <h2>Criar Nova Receita</h2>
                <form id="form-receita" action="/Story-Bytes-/pages/processa_receita.php" method="POST" enctype="multipart/form-data" class="receita-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="titulo">Título da Receita</label>
                            <input type="text" id="titulo" name="titulo" required 
                                   placeholder="Ex: Bolo de Chocolate da Vovó">
                        </div>
                        
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
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
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" rows="3" required
                                  placeholder="Conte a história desta receita..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ingredientes">Ingredientes</label>
                        <textarea id="ingredientes" name="ingredientes" rows="6" required
                                  placeholder="Liste os ingredientes, um por linha:&#10;- 2 xícaras de farinha&#10;- 3 ovos&#10;- 1 xícara de açúcar"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="modo_preparo">Modo de Preparo</label>
                        <textarea id="modo_preparo" name="modoprep" rows="8" required
                                  placeholder="Descreva o passo a passo:&#10;1. Pré-aqueça o forno...&#10;2. Misture os ingredientes secos..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rendimento">Rendimento</label>
                            <input type="text" id="rendimento" name="rendimento" 
                                   placeholder="Ex: 8 porções, 12 unidades, 1 litro">
                            <small class="form-hint">Informe a quantidade e unidade (porções, unidades, litros, etc.)</small>
                        </div>

                        <div class="form-group">
                            <label for="tempo_preparo">Tempo de Preparo</label>
                            <input type="text" id="tempo_preparo" name="tempo_preparo" 
                                   placeholder="Ex: 45 minutos, 2 horas">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="imagem">Imagem da Receita</label>
                        <input type="file" id="imagem" name="imagem" accept="image/*">
                        <small>Formato: JPG, PNG. Tamanho máximo: 2MB</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="acao" value="salvar" class="btn-primary">Salvar Receita</button>
                        <button type="reset" class="btn-outline">Limpar Formulário</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Aba: Minhas Receitas -->
        <div class="tab-content" id="tab-minhas">
            <h2>Minhas Receitas</h2>
            
            <?php if ($receitas_usuario && $receitas_usuario->num_rows > 0): ?>
                <div class="receitas-grid">
                    <?php 
                    $receitas_usuario->data_seek(0); // Resetar o ponteiro
                    while($receita = $receitas_usuario->fetch_assoc()): 
                    ?>
                        <div class="receita-card" data-receita-id="<?= $receita['id'] ?>">
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
                                        $status_text = 'Rascunho';
                                        break;
                                    default:
                                        $status_class = 'status-receita';
                                        $status_text = 'Receita';
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
                                        <img src="./img/receitas/<?= htmlspecialchars($receita['imagem']) ?>" 
                                             alt="<?= htmlspecialchars($receita['titulo']) ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-actions">
                                <button class="btn-small btn-view" onclick="obterReceita(<?= $receita['id'] ?>)">Ver</button>
                                <button class="btn-small btn-edit" onclick="editarReceita(<?= $receita['id'] ?>)">Editar</button>
                                <button class="btn-small btn-delete" onclick="excluirReceita(<?= $receita['id'] ?>)">Excluir</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Você ainda não criou nenhuma receita.</p>
                    <p>Que tal compartilhar sua primeira criação culinária?</p>
                    <button class="btn-primary" onclick="switchTab('criar')">Criar Primeira Receita</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Aba: Meus Dados -->
        <div class="tab-content" id="tab-dados">
            <h2>Meus Dados</h2>
            
            <!-- Botões de alternância -->
            <div class="dados-toggle">
                <button class="toggle-btn active" id="btn-visualizar" onclick="toggleDadosMode('visualizar')">Visualizar</button>
                <button class="toggle-btn" id="btn-editar" onclick="toggleDadosMode('editar')">Editar</button>
            </div>
            
            <!-- Modo Visualização -->
            <div class="dados-container" id="dados-visualizar">
                <div class="info-group">
                    <label>Nome Completo:</label>
                    <p><?= htmlspecialchars($dados_usuario['nome']) ?></p>
                </div>
                
                <div class="info-group">
                    <label>E-mail:</label>
                    <p><?= htmlspecialchars($dados_usuario['email']) ?></p>
                </div>
                
                <div class="info-group">
                    <label>Data de Cadastro:</label>
                    <p><?= date('d/m/Y', strtotime($dados_usuario['dataCadastro'])) ?></p>
                </div>
                
                <div class="info-group">
                    <label>Tipo de Usuário:</label>
                    <p><?= $dados_usuario['tipo_usuario'] === 'admin' ? 'Administrador' : 'Chef Caseiro' ?></p>
                </div>
                
                <div class="actions">
                    <button class="btn-primary" onclick="toggleDadosMode('editar')">Editar Dados</button>
                    <button class="btn-secondary" onclick="toggleDadosMode('senha')">Alterar Senha</button>
                </div>
            </div>
            
            <!-- Modo Edição -->
            <div class="dados-container hidden" id="dados-editar">
                <form id="form-editar-dados" class="dados-form">
                    <div class="form-group">
                        <label for="edit-nome">Nome Completo</label>
                        <input type="text" id="edit-nome" name="nome" value="<?= htmlspecialchars($dados_usuario['nome']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit-email">E-mail</label>
                        <input type="email" id="edit-email" name="email" value="<?= htmlspecialchars($dados_usuario['email']) ?>" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-primary" onclick="salvarDados()">Salvar Alterações</button>
                        <button type="button" class="btn-secondary" onclick="toggleDadosMode('visualizar')">Cancelar</button>
                    </div>
                </form>
            </div>
            
            <!-- Modo Alterar Senha -->
            <div class="dados-container hidden" id="dados-senha">
                <form id="form-alterar-senha" class="dados-form">
                    <div class="form-group">
                        <label for="senha-atual">Senha Atual</label>
                        <input type="password" id="senha-atual" name="senha_atual" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nova-senha">Nova Senha</label>
                        <input type="password" id="nova-senha" name="nova_senha" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar-senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar-senha" name="confirmar_senha" required minlength="6">
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-primary" onclick="alterarSenha()">Alterar Senha</button>
                        <button type="button" class="btn-secondary" onclick="toggleDadosMode('visualizar')">Cancelar</button>
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
                    <h3>Descrição</h3>
                    <p id="modal-descricao"></p>
                </div>
                
                <div class="info-section">
                    <h3>Ingredientes</h3>
                    <div id="modal-ingredientes"></div>
                </div>
                
                <div class="info-section">
                    <h3>Modo de Preparo</h3>
                    <div id="modal-modo-preparo"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-item">
                        <h4>Rendimento</h4>
                        <p id="modal-rendimento"></p>
                    </div>
                    <div class="info-item">
                        <h4>Tempo de Preparo</h4>
                        <p id="modal-tempo"></p>
                    </div>
                    <div class="info-item">
                        <h4>Criada em</h4>
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
    console.log('toggleDadosMode chamado com modo:', modo);
    
    // Verificar se os elementos existem
    const visualizar = document.getElementById('dados-visualizar');
    const editar = document.getElementById('dados-editar');
    const senha = document.getElementById('dados-senha');
    
    if (!visualizar || !editar || !senha) {
        console.error('Elementos não encontrados:', {visualizar, editar, senha});
        return;
    }
    
    // Esconder todos os containers
    visualizar.classList.add('hidden');
    editar.classList.add('hidden');
    senha.classList.add('hidden');
    
    // Mostrar o container selecionado
    if (modo === 'editar') {
        editar.classList.remove('hidden');
        console.log('Modo editar ativado');
    } else if (modo === 'senha') {
        senha.classList.remove('hidden');
        console.log('Modo senha ativado');
    } else {
        // Padrão: mostrar visualização
        visualizar.classList.remove('hidden');
        console.log('Modo visualizar ativado');
    }
}

// Adicionar event listeners quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, configurando event listeners');
    
    // Event listeners para os botões de dados
    const btnEditarDados = document.querySelector('button[onclick="toggleDadosMode(\'editar\')"]');
    const btnAlterarSenha = document.querySelector('button[onclick="toggleDadosMode(\'senha\')"]');
    
    if (btnEditarDados) {
        btnEditarDados.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botão Editar Dados clicado');
            toggleDadosMode('editar');
        });
    }
    
    if (btnAlterarSenha) {
        btnAlterarSenha.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botão Alterar Senha clicado');
            toggleDadosMode('senha');
        });
    }
    
    // Verificar se os elementos existem no DOM
    console.log('Elementos encontrados:', {
        visualizar: document.getElementById('dados-visualizar'),
        editar: document.getElementById('dados-editar'),
        senha: document.getElementById('dados-senha'),
        btnEditar: btnEditarDados,
        btnSenha: btnAlterarSenha
    });
});

// Event listeners para os botões das abas
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const tabName = btn.getAttribute('data-tab');
        switchTab(tabName);
    });
});

// Função para formatar rendimento
function formatarRendimento(rendimento) {
    if (!rendimento) {
        return 'Não informado';
    }
    
    // Se já tem formatação completa (contém letras), retorna como está
    if (/[a-zA-Z]/.test(rendimento)) {
        return rendimento;
    }
    
    // Se é apenas número, adiciona formatação padrão
    const numero = parseInt(rendimento);
    if (!isNaN(numero)) {
        if (numero === 1) {
            return numero + ' porção';
        } else {
            return numero + ' porções';
        }
    }
    
    // Se não conseguir processar, retorna como está
    return rendimento;
}

// Funções para gerenciamento de receitas
function obterReceita(id) {
    // Fazer uma requisição AJAX para buscar os dados completos da receita
    fetch('/Story-Bytes-/pages/obter_receita.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarModalReceita(data.receita);
            } else {
                alert('Erro ao carregar receita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao carregar receita');
        });
}

function mostrarModalReceita(receita) {
    const modal = document.getElementById('modalReceita');
    
    document.getElementById('modal-titulo').textContent = receita.titulo;
    document.getElementById('modal-descricao').textContent = receita.descricao;
    document.getElementById('modal-ingredientes').innerHTML = receita.ingredientes.replace(/\n/g, '<br>');
    document.getElementById('modal-modo-preparo').innerHTML = receita.modoprep.replace(/\n/g, '<br>');
    document.getElementById('modal-rendimento').textContent = formatarRendimento(receita.rendimento);
    document.getElementById('modal-tempo').textContent = receita.tempo_preparo || 'Não informado';
    document.getElementById('modal-data').textContent = new Date(receita.datacriacao).toLocaleDateString('pt-BR');
    
    const modalImagem = document.getElementById('modal-imagem');
    if (receita.imagem) {
        modalImagem.src = './img/receitas/' + receita.imagem;
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
    window.location.href = '/Story-Bytes-/pages/editar_receita.php?id=' + id;
}

// Função para atualizar contador de receitas no header
function atualizarContadorReceitas(incremento) {
    const receitasCount = document.querySelector('.receitas-count');
    if (receitasCount) {
        // Extrair número atual do texto "Receitas Criadas: X"
        const textoAtual = receitasCount.textContent;
        const numeroAtual = parseInt(textoAtual.match(/\d+/)[0]);
        const novoNumero = Math.max(0, numeroAtual + incremento);
        
        // Atualizar texto
        receitasCount.textContent = `Receitas Criadas: ${novoNumero}`;
        
        // Animação visual
        receitasCount.style.transition = 'color 0.3s ease, transform 0.3s ease';
        receitasCount.style.color = incremento > 0 ? '#4CAF50' : '#ff7043';
        receitasCount.style.transform = 'scale(1.1)';
        
        setTimeout(() => {
            receitasCount.style.color = '';
            receitasCount.style.transform = 'scale(1)';
        }, 300);
    }
}

function excluirReceita(id) {
    if (confirm('Tem certeza que deseja excluir esta receita?\n\nEsta ação não pode ser desfeita!')) {
        // Encontrar o cartão da receita para remover da interface
        const receitaCard = document.querySelector(`[data-receita-id="${id}"]`);
        
        // Mostrar indicador de carregamento
        if (receitaCard) {
            receitaCard.style.opacity = '0.6';
            receitaCard.style.pointerEvents = 'none';
        }
        
        fetch('/Story-Bytes-/pages/excluir_receita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remover o cartão da receita da interface imediatamente
                if (receitaCard) {
                    receitaCard.style.transition = 'all 0.5s ease';
                    receitaCard.style.transform = 'scale(0.8)';
                    receitaCard.style.opacity = '0';
                    
                    setTimeout(() => {
                        receitaCard.remove();
                        
                        // Atualizar contador de receitas criadas
                        atualizarContadorReceitas(-1);
                        
                        // Verificar se ainda há receitas na grid
                        const receitasGrid = document.querySelector('.receitas-grid');
                        const remainingCards = receitasGrid.querySelectorAll('.receita-card');
                        
                        if (remainingCards.length === 0) {
                            // Se não há mais receitas, mostrar mensagem de vazio
                            receitasGrid.innerHTML = `
                                <div class="sem-receitas">
                                    <p>Você ainda não criou nenhuma receita.</p>
                                    <p>Que tal compartilhar sua primeira criação culinária?</p>
                                    <button class="btn-primary" onclick="switchTab('criar')">Criar Primeira Receita</button>
                                </div>
                            `;
                        }
                    }, 500);
                }
                
                // Mostrar mensagem de sucesso mais elegante
                alert(data.message || 'Receita excluída com sucesso!');
            } else {
                // Restaurar o cartão em caso de erro
                if (receitaCard) {
                    receitaCard.style.opacity = '1';
                    receitaCard.style.pointerEvents = 'auto';
                }
                alert('Erro ao excluir receita: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            // Restaurar o cartão em caso de erro
            if (receitaCard) {
                receitaCard.style.opacity = '1';
                receitaCard.style.pointerEvents = 'auto';
            }
            alert('Erro ao conectar com o servidor. Tente novamente.');
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

// Função para salvar dados usando AJAX
function salvarDados() {
    const nome = document.getElementById('edit-nome').value.trim();
    const email = document.getElementById('edit-email').value.trim();
    
    if (!nome || !email) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    // Mostrar indicador de carregamento
    const botaoSalvar = document.querySelector('#dados-editar .btn-primary');
    const textoOriginal = botaoSalvar.textContent;
    botaoSalvar.textContent = 'Salvando...';
    botaoSalvar.disabled = true;
    
    const formData = new FormData();
    formData.append('nome', nome);
    formData.append('email', email);
    
    fetch('/Story-Bytes-/pages/atualizar_dados_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar os dados na visualização
            document.querySelector('#dados-visualizar .info-group:nth-child(1) p').textContent = nome;
            document.querySelector('#dados-visualizar .info-group:nth-child(2) p').textContent = email;
            
            // Voltar para o modo visualizar
            toggleDadosMode('visualizar');
            
            // Mostrar mensagem de sucesso
            alert('Dados atualizados com sucesso!');
        } else {
            alert('Erro ao atualizar dados: ' + data.message);
        }
        
        // Restaurar botão
        botaoSalvar.textContent = textoOriginal;
        botaoSalvar.disabled = false;
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
        
        // Restaurar botão
        botaoSalvar.textContent = textoOriginal;
        botaoSalvar.disabled = false;
    });
}

// Função para alterar senha usando AJAX
function alterarSenha() {
    const senhaAtual = document.getElementById('senha-atual').value;
    const novaSenha = document.getElementById('nova-senha').value;
    const confirmarSenha = document.getElementById('confirmar-senha').value;
    
    // Validações no frontend
    if (!senhaAtual || !novaSenha || !confirmarSenha) {
        alert('Por favor, preencha todos os campos');
        return;
    }
    
    if (novaSenha.length < 6) {
        alert('A nova senha deve ter pelo menos 6 caracteres');
        return;
    }
    
    if (novaSenha !== confirmarSenha) {
        alert('A confirmação da senha não confere');
        return;
    }
    
    // Mostrar indicador de carregamento
    const botaoAlterar = document.querySelector('#dados-senha .btn-primary');
    const textoOriginal = botaoAlterar.textContent;
    botaoAlterar.textContent = 'Alterando...';
    botaoAlterar.disabled = true;
    
    const formData = new FormData();
    formData.append('senha_atual', senhaAtual);
    formData.append('nova_senha', novaSenha);
    formData.append('confirmar_senha', confirmarSenha);
    
    fetch('/Story-Bytes-/pages/atualizar_senha_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpar campos
            document.getElementById('senha-atual').value = '';
            document.getElementById('nova-senha').value = '';
            document.getElementById('confirmar-senha').value = '';
            
            // Voltar para o modo visualizar
            toggleDadosMode('visualizar');
            
            // Mostrar mensagem de sucesso
            alert('Senha alterada com sucesso!');
        } else {
            alert('Erro ao alterar senha: ' + data.message);
        }
        
        // Restaurar botão
        botaoAlterar.textContent = textoOriginal;
        botaoAlterar.disabled = false;
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
        
        // Restaurar botão
        botaoAlterar.textContent = textoOriginal;
        botaoAlterar.disabled = false;
    });
}

// Processar formulário de receita via AJAX
document.getElementById('form-receita').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const botaoSubmit = e.submitter;
    const textoOriginal = botaoSubmit.textContent;
    
    // Mostrar loading
    botaoSubmit.textContent = 'Enviando...';
    botaoSubmit.disabled = true;
    
    fetch('/Story-Bytes-/pages/processa_receita_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar contador de receitas criadas
            atualizarContadorReceitas(1);
            
            // Limpar formulário
            document.getElementById('form-receita').reset();
            
            // Mostrar mensagem de sucesso
            alert(data.message);
            
            // Mudar para a aba "Minhas Receitas" após 1 segundo
            setTimeout(() => {
                switchTab('minhas');
                // Recarregar a página para mostrar a nova receita
                window.location.reload();
            }, 1000);
            
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
    })
    .finally(() => {
        // Restaurar botão
        botaoSubmit.textContent = textoOriginal;
        botaoSubmit.disabled = false;
    });
});

// Detectar parâmetro de URL para ativar aba específica
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam && (tabParam === 'minhas' || tabParam === 'criar' || tabParam === 'dados')) {
        switchTab(tabParam);
        
        // Se for a aba dados, garantir que esteja no modo visualizar
        if (tabParam === 'dados') {
            setTimeout(function() {
                toggleDadosMode('visualizar');
            }, 100);
        }
    }
});
</script>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>