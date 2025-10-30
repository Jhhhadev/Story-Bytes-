<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Categoria das Receitas - Story-Bytes</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; 
            padding: 20px;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #ff7043 0%, #ff8a65 100%); 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .header p { font-size: 1.1rem; opacity: 0.9; }
        .content { padding: 30px; }
        .section { margin-bottom: 40px; }
        .section h2 { 
            color: #333; 
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 3px solid #ff7043; 
            font-size: 1.5rem;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #555; 
        }
        .form-control { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #ddd; 
            border-radius: 8px; 
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-control:focus { 
            outline: none; 
            border-color: #ff7043; 
            box-shadow: 0 0 0 3px rgba(255, 112, 67, 0.1);
        }
        .btn { 
            background: linear-gradient(135deg, #ff7043 0%, #ff8a65 100%); 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(255, 112, 67, 0.3);
        }
        .btn-success { background: linear-gradient(135deg, #28a745 0%, #34ce57 100%); }
        .btn-info { background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); }
        .recipe-list { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); 
            gap: 20px; 
            margin-top: 20px;
        }
        .recipe-card { 
            border: 2px solid #e9ecef; 
            border-radius: 12px; 
            padding: 20px; 
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .recipe-card:hover { 
            border-color: #ff7043; 
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .recipe-title { 
            font-size: 1.2rem; 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 8px; 
        }
        .recipe-category { 
            background: #ff7043; 
            color: white; 
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 10px;
        }
        .recipe-desc { 
            color: #666; 
            font-size: 0.9rem; 
            line-height: 1.4;
            margin-bottom: 15px;
        }
        .alert { 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            border: 1px solid;
        }
        .alert-success { 
            background: #d4edda; 
            border-color: #c3e6cb; 
            color: #155724; 
        }
        .alert-danger { 
            background: #f8d7da; 
            border-color: #f5c6cb; 
            color: #721c24; 
        }
        .alert-info { 
            background: #cce7ff; 
            border-color: #b6d7ff; 
            color: #004085; 
        }
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px;
        }
        .stat-card { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 20px; 
            border-radius: 12px; 
            text-align: center;
        }
        .stat-number { 
            font-size: 2rem; 
            font-weight: 700; 
            margin-bottom: 5px; 
        }
        .stat-label { 
            font-size: 0.9rem; 
            opacity: 0.9; 
        }
        .change-form { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 12px; 
            border: 2px dashed #dee2e6;
        }
        .btn-group { 
            display: flex; 
            gap: 10px; 
            margin-top: 15px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍳 Gerenciar Categorias das Receitas</h1>
            <p>Altere as categorias das receitas de forma rápida e organizada</p>
        </div>
        
        <div class="content">
            <?php
            require_once 'backend/conexao.php';
            
            // Processar alteração de categoria
            if ($_POST['acao'] === 'alterar_categoria' && isset($_POST['receita_id'], $_POST['nova_categoria'])) {
                $receita_id = (int)$_POST['receita_id'];
                $nova_categoria_id = (int)$_POST['nova_categoria'];
                
                $sql_update = "UPDATE receita SET categoria_id = ? WHERE id = ?";
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("ii", $nova_categoria_id, $receita_id);
                
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success">✅ <strong>Sucesso!</strong> Categoria da receita alterada com sucesso!</div>';
                } else {
                    echo '<div class="alert alert-danger">❌ <strong>Erro!</strong> Falha ao alterar categoria: ' . $conn->error . '</div>';
                }
                $stmt->close();
            }
            
            // Processar alteração em lote
            if ($_POST['acao'] === 'alterar_lote' && isset($_POST['receitas_selecionadas'], $_POST['categoria_lote'])) {
                $receitas_ids = $_POST['receitas_selecionadas'];
                $categoria_lote_id = (int)$_POST['categoria_lote'];
                $total_alteradas = 0;
                
                foreach ($receitas_ids as $receita_id) {
                    $receita_id = (int)$receita_id;
                    $sql_update = "UPDATE receita SET categoria_id = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql_update);
                    $stmt->bind_param("ii", $categoria_lote_id, $receita_id);
                    
                    if ($stmt->execute()) {
                        $total_alteradas++;
                    }
                    $stmt->close();
                }
                
                echo '<div class="alert alert-success">✅ <strong>Sucesso!</strong> ' . $total_alteradas . ' receitas tiveram suas categorias alteradas!</div>';
            }
            
            // Buscar estatísticas
            $sql_stats = "SELECT 
                (SELECT COUNT(*) FROM receita) as total_receitas,
                (SELECT COUNT(*) FROM categoria) as total_categorias,
                (SELECT COUNT(*) FROM receita WHERE categoria_id IS NULL) as sem_categoria";
            $stats = $conn->query($sql_stats)->fetch_assoc();
            
            // Buscar categorias por quantidade de receitas
            $sql_cat_stats = "SELECT c.nome, c.id, COUNT(r.id) as quantidade 
                             FROM categoria c 
                             LEFT JOIN receita r ON c.id = r.categoria_id 
                             GROUP BY c.id, c.nome 
                             ORDER BY quantidade DESC, c.nome";
            $cat_stats = $conn->query($sql_cat_stats);
            ?>
            
            <!-- Estatísticas -->
            <div class="section">
                <h2>📊 Estatísticas Gerais</h2>
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_receitas'] ?></div>
                        <div class="stat-label">Total de Receitas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_categorias'] ?></div>
                        <div class="stat-label">Categorias Disponíveis</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['sem_categoria'] ?></div>
                        <div class="stat-label">Receitas Sem Categoria</div>
                    </div>
                </div>
                
                <h3>📈 Receitas por Categoria:</h3>
                <div class="recipe-list">
                    <?php while ($cat = $cat_stats->fetch_assoc()): ?>
                        <div class="recipe-card">
                            <div class="recipe-title"><?= htmlspecialchars($cat['nome']) ?></div>
                            <div class="recipe-category"><?= $cat['quantidade'] ?> receita<?= $cat['quantidade'] != 1 ? 's' : '' ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <!-- Alterar categoria individual -->
            <div class="section">
                <h2>🔄 Alterar Categoria Individual</h2>
                <div class="change-form">
                    <form method="POST" action="">
                        <input type="hidden" name="acao" value="alterar_categoria">
                        
                        <div class="form-group">
                            <label>Selecione a Receita:</label>
                            <select name="receita_id" class="form-control" required>
                                <option value="">-- Escolha uma receita --</option>
                                <?php
                                $sql_receitas = "SELECT r.id, r.titulo, c.nome as categoria_atual 
                                               FROM receita r 
                                               LEFT JOIN categoria c ON r.categoria_id = c.id 
                                               ORDER BY r.titulo";
                                $receitas = $conn->query($sql_receitas);
                                while ($receita = $receitas->fetch_assoc()):
                                ?>
                                    <option value="<?= $receita['id'] ?>">
                                        <?= htmlspecialchars($receita['titulo']) ?> 
                                        (Atual: <?= $receita['categoria_atual'] ?: 'Sem categoria' ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Nova Categoria:</label>
                            <select name="nova_categoria" class="form-control" required>
                                <option value="">-- Escolha a nova categoria --</option>
                                <?php
                                $sql_categorias = "SELECT id, nome FROM categoria ORDER BY nome";
                                $categorias = $conn->query($sql_categorias);
                                while ($categoria = $categorias->fetch_assoc()):
                                ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn">🔄 Alterar Categoria</button>
                    </form>
                </div>
            </div>
            
            <!-- Alterar categoria em lote -->
            <div class="section">
                <h2>📦 Alterar Categoria em Lote</h2>
                <div class="change-form">
                    <form method="POST" action="">
                        <input type="hidden" name="acao" value="alterar_lote">
                        
                        <div class="form-group">
                            <label>Filtrar por Categoria Atual:</label>
                            <select id="filtro_categoria" class="form-control">
                                <option value="">-- Todas as categorias --</option>
                                <option value="null">Sem categoria</option>
                                <?php
                                $categorias->data_seek(0); // Reset pointer
                                while ($categoria = $categorias->fetch_assoc()):
                                ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Selecione as Receitas:</label>
                            <div id="lista_receitas" class="recipe-list">
                                <?php
                                $sql_todas_receitas = "SELECT r.id, r.titulo, r.descricao, c.nome as categoria_atual, c.id as categoria_id
                                                     FROM receita r 
                                                     LEFT JOIN categoria c ON r.categoria_id = c.id 
                                                     ORDER BY r.titulo";
                                $todas_receitas = $conn->query($sql_todas_receitas);
                                while ($receita = $todas_receitas->fetch_assoc()):
                                ?>
                                    <div class="recipe-card" data-categoria="<?= $receita['categoria_id'] ?: 'null' ?>">
                                        <div class="recipe-title">
                                            <input type="checkbox" name="receitas_selecionadas[]" value="<?= $receita['id'] ?>" style="margin-right: 8px;">
                                            <?= htmlspecialchars($receita['titulo']) ?>
                                        </div>
                                        <div class="recipe-category">
                                            <?= $receita['categoria_atual'] ?: 'Sem categoria' ?>
                                        </div>
                                        <div class="recipe-desc">
                                            <?= htmlspecialchars(substr($receita['descricao'], 0, 100)) ?>...
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Nova Categoria para as Receitas Selecionadas:</label>
                            <select name="categoria_lote" class="form-control" required>
                                <option value="">-- Escolha a nova categoria --</option>
                                <?php
                                $categorias->data_seek(0); // Reset pointer
                                while ($categoria = $categorias->fetch_assoc()):
                                ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="btn-group">
                            <button type="button" onclick="selecionarTodas()" class="btn btn-info">✅ Selecionar Todas Visíveis</button>
                            <button type="button" onclick="deselecionarTodas()" class="btn btn-info">❌ Desmarcar Todas</button>
                            <button type="submit" class="btn btn-success">📦 Alterar Categorias em Lote</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Ações rápidas -->
            <div class="section">
                <h2>⚡ Ações Rápidas</h2>
                <div class="alert alert-info">
                    <strong>💡 Dicas:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        <li>Use o filtro por categoria para encontrar receitas específicas mais rapidamente</li>
                        <li>A alteração em lote é ideal para organizar muitas receitas de uma vez</li>
                        <li>Sempre verifique as alterações antes de confirmar</li>
                        <li>As estatísticas são atualizadas automaticamente após cada alteração</li>
                    </ul>
                </div>
                
                <div class="btn-group">
                    <button onclick="location.reload()" class="btn btn-info">🔄 Atualizar Página</button>
                    <button onclick="window.open('/Story-Bytes-/pages/buscar.php', '_blank')" class="btn">👀 Ver Site</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Filtro por categoria
        document.getElementById('filtro_categoria').addEventListener('change', function() {
            const filtro = this.value;
            const cards = document.querySelectorAll('#lista_receitas .recipe-card');
            
            cards.forEach(card => {
                const categoria = card.getAttribute('data-categoria');
                if (filtro === '' || categoria === filtro) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Selecionar todas as receitas visíveis
        function selecionarTodas() {
            const checkboxes = document.querySelectorAll('#lista_receitas .recipe-card:not([style*="display: none"]) input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = true);
        }
        
        // Desmarcar todas as receitas
        function deselecionarTodas() {
            const checkboxes = document.querySelectorAll('#lista_receitas input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        }
        
        // Confirmar antes de alterar em lote
        document.querySelector('form[action=""] input[value="alterar_lote"]').closest('form').addEventListener('submit', function(e) {
            const selecionadas = document.querySelectorAll('#lista_receitas input[type="checkbox"]:checked').length;
            if (selecionadas === 0) {
                e.preventDefault();
                alert('❌ Selecione pelo menos uma receita para alterar!');
                return;
            }
            
            if (!confirm(`🤔 Tem certeza que deseja alterar a categoria de ${selecionadas} receita(s)?`)) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>