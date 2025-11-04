<?php
// ============================================
// PÁGINA ADMINISTRATIVA - GERENCIAR CATEGORIAS
// ============================================

session_start();

// Verificar se é admin (descomente se quiser restringir acesso)
// if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

$ACTIVE_PAGE = 'admin';
$PAGE_TITLE  = 'StoryBites — Gerenciar Categorias';
$PAGE_DESC   = 'Painel administrativo para gerenciar categorias de receitas';
$PAGE_STYLES = [
                'css/variables.css',
                'css/gerenciar-categorias.css'
];

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
include('../backend/conexao.php');

// Processar ações
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'excluir_categoria':
                $categoria_id = (int)$_POST['categoria_id'];
                
                // Verificar se há receitas nesta categoria
                $sql_check = "SELECT COUNT(*) as total FROM receita WHERE categoria_id = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("i", $categoria_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                $total_receitas = $result_check->fetch_assoc()['total'];
                
                if ($total_receitas > 0) {
                    $mensagem = "Não é possível excluir a categoria. Ela possui $total_receitas receita(s). Mova as receitas primeiro.";
                    $tipo_mensagem = 'erro';
                } else {
                    // Excluir categoria
                    $sql_delete = "DELETE FROM categoria WHERE id = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $categoria_id);
                    
                    if ($stmt_delete->execute()) {
                        $mensagem = "Categoria excluída com sucesso!";
                        $tipo_mensagem = 'sucesso';
                    } else {
                        $mensagem = "Erro ao excluir categoria: " . $conn->error;
                        $tipo_mensagem = 'erro';
                    }
                }
                break;
                
            case 'mover_receitas':
                $categoria_origem = (int)$_POST['categoria_origem'];
                $categoria_destino = (int)$_POST['categoria_destino'];
                
                $sql_move = "UPDATE receita SET categoria_id = ? WHERE categoria_id = ?";
                $stmt_move = $conn->prepare($sql_move);
                $stmt_move->bind_param("ii", $categoria_destino, $categoria_origem);
                
                if ($stmt_move->execute()) {
                    $mensagem = "Receitas movidas com sucesso! Agora você pode excluir a categoria vazia.";
                    $tipo_mensagem = 'sucesso';
                } else {
                    $mensagem = "Erro ao mover receitas: " . $conn->error;
                    $tipo_mensagem = 'erro';
                }
                break;
        }
    }
}

// Buscar todas as categorias com contagem de receitas
$sql_categorias = "SELECT 
    c.id,
    c.nome,
    COUNT(r.id) as total_receitas
FROM categoria c
LEFT JOIN receita r ON c.id = r.categoria_id
GROUP BY c.id, c.nome
ORDER BY c.nome";

$categorias = $conn->query($sql_categorias);
?>

<main class="container">
    <a href="/Story-Bytes-/pages/perfil.php" class="back-link">← Voltar ao Perfil</a>
    
    <h1>🗂️ Gerenciar Categorias</h1>
        
        <?php if ($mensagem): ?>
            <div class="mensagem <?= $tipo_mensagem ?>"><?= $mensagem ?></div>
        <?php endif; ?>
        
        <div class="aviso">
            <strong>Atenção:</strong> Antes de excluir uma categoria, certifique-se de que ela não possui receitas. 
            Se houver receitas, mova-as para outra categoria primeiro.
        </div>
        
        <h2>📊 Categorias Existentes</h2>
        
        <?php if ($categorias && $categorias->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Categoria</th>
                        <th>Total de Receitas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($categoria = $categorias->fetch_assoc()): ?>
                        <tr>
                            <td><?= $categoria['id'] ?></td>
                            <td><strong><?= htmlspecialchars($categoria['nome']) ?></strong></td>
                            <td>
                                <span class="badge-receitas <?= $categoria['total_receitas'] > 0 ? 'com-receitas' : 'sem-receitas' ?>">
                                    <?= $categoria['total_receitas'] ?> receita<?= $categoria['total_receitas'] != 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($categoria['total_receitas'] == 0): ?>
                                    <form method="post" class="form-excluir" onsubmit="return confirm('Tem certeza que deseja excluir a categoria \'<?= htmlspecialchars($categoria['nome']) ?>\'?')">
                                        <input type="hidden" name="acao" value="excluir_categoria">
                                        <input type="hidden" name="categoria_id" value="<?= $categoria['id'] ?>">
                                        <button type="submit" class="btn btn-danger">🗑️ Excluir</button>
                                    </form>
                                <?php else: ?>
                                    <span class="texto-aviso">Mova as receitas primeiro</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma categoria encontrada.</p>
        <?php endif; ?>
        
        <h2>📦 Mover Receitas Entre Categorias</h2>
        <p>Use esta função para mover todas as receitas de uma categoria para outra antes de excluir.</p>
        
        <form method="post" class="form-inline">
            <input type="hidden" name="acao" value="mover_receitas">
            
            <label>De:</label>
            <select name="categoria_origem" required>
                <option value="">Selecione a categoria origem</option>
                <?php 
                // Reset do resultado para usar novamente
                $categorias->data_seek(0);
                while($cat = $categorias->fetch_assoc()): 
                    if ($cat['total_receitas'] > 0):
                ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?> (<?= $cat['total_receitas'] ?> receitas)</option>
                <?php 
                    endif;
                endwhile; 
                ?>
            </select>
            
            <label>Para:</label>
            <select name="categoria_destino" required>
                <option value="">Selecione a categoria destino</option>
                <?php 
                $categorias->data_seek(0);
                while($cat = $categorias->fetch_assoc()): 
                ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endwhile; ?>
            </select>
            
            <button type="submit" class="btn btn-warning" onclick="return confirm('Tem certeza que deseja mover todas as receitas?')">
                🔄 Mover Receitas
            </button>
        </form>
        
        <h2>📋 Comandos SQL Diretos</h2>
        <p>Para operações avançadas, você pode usar os comandos SQL no arquivo:</p>
        <code>scripts_sql_gerenciar_categorias.sql</code>
        
        <div class="rodape-pagina">
            <a href="/Story-Bytes-/index.php" class="btn btn-primary">🏠 Voltar ao Início</a>
        </div>
</main>

<?php
require_once APP_ROOT . '/partials/_footer.php';
$conn->close();
?>