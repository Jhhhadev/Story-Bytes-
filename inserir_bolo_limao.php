<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Bolo de Limão - Story-Bytes</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #ffd54f 0%, #ffb74d 100%);
            min-height: 100vh; 
            padding: 20px;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%); 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .header p { font-size: 1.1rem; opacity: 0.9; }
        .content { padding: 30px; }
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
        .btn { 
            background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%); 
            color: white; 
            padding: 12px 25px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 10px 5px;
        }
        .btn:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }
        .btn-success { background: linear-gradient(135deg, #28a745 0%, #34ce57 100%); }
        .recipe-preview { 
            background: #f8f9fa; 
            border: 2px solid #dee2e6; 
            border-radius: 12px; 
            padding: 20px; 
            margin: 20px 0;
        }
        .recipe-title { 
            color: #ff9800; 
            font-size: 1.5rem; 
            font-weight: 600; 
            margin-bottom: 15px;
        }
        .recipe-section { 
            margin-bottom: 20px; 
        }
        .recipe-section h4 { 
            color: #333; 
            margin-bottom: 10px; 
        }
        .recipe-section p { 
            line-height: 1.6; 
            color: #555; 
        }
        .ingredients, .instructions { 
            white-space: pre-line; 
            background: white; 
            padding: 15px; 
            border-radius: 8px; 
            border-left: 4px solid #ff9800;
        }
        .stats { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
            gap: 15px; 
            margin: 20px 0;
        }
        .stat-item { 
            background: #ff9800; 
            color: white; 
            padding: 15px; 
            border-radius: 8px; 
            text-align: center;
        }
        .stat-item strong { 
            display: block; 
            font-size: 1.1rem; 
        }
        .emoji { font-size: 2rem; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="emoji">🍋</div>
            <h1>Inserir Receita: Bolo de Limão</h1>
            <p>Adicione esta deliciosa receita ao banco de dados do Story-Bytes</p>
        </div>
        
        <div class="content">
            <?php
            require_once 'backend/conexao.php';
            
            $inseriu = false;
            $erro = '';
            
            // Processar inserção
            if ($_POST['acao'] === 'inserir') {
                // Verificar se usuário existe
                $sql_usuario = "SELECT id FROM usuario LIMIT 1";
                $result_usuario = $conn->query($sql_usuario);
                
                if ($result_usuario->num_rows === 0) {
                    $erro = "Erro: Nenhum usuário encontrado no sistema. Cadastre um usuário primeiro.";
                } else {
                    $usuario = $result_usuario->fetch_assoc();
                    $usuario_id = $usuario['id'];
                    
                    // Verificar se categoria Doces existe
                    $sql_categoria = "SELECT id FROM categoria WHERE nome = 'Doces'";
                    $result_categoria = $conn->query($sql_categoria);
                    
                    if ($result_categoria->num_rows === 0) {
                        $erro = "Erro: Categoria 'Doces' não encontrada. Execute o script de criação de categorias primeiro.";
                    } else {
                        $categoria = $result_categoria->fetch_assoc();
                        $categoria_id = $categoria['id'];
                        
                        // Verificar se a receita já existe
                        $sql_check = "SELECT id FROM receita WHERE titulo = 'Bolo de Limão Cremoso'";
                        $result_check = $conn->query($sql_check);
                        
                        if ($result_check->num_rows > 0) {
                            $erro = "Esta receita já existe no banco de dados!";
                        } else {
                            // Dados da receita
                            $titulo = "Bolo de Limão Cremoso";
                            $descricao = "Um delicioso bolo de limão macio e suculento, perfeito para acompanhar um café da tarde. Com sabor cítrico marcante e textura fofa, este bolo é uma sobremesa irresistível que agrada toda a família.";
                            
                            $ingredientes = "**Massa:**
• 3 ovos
• 1 xícara de açúcar
• 1/2 xícara de óleo
• 1 xícara de leite
• 2 xícaras de farinha de trigo
• 1 colher de sopa de fermento em pó
• Raspas de 2 limões
• 1/4 xícara de suco de limão

**Calda:**
• 1/2 xícara de açúcar
• 1/3 xícara de suco de limão
• 2 colheres de sopa de água

**Cobertura (opcional):**
• 1 xícara de açúcar de confeiteiro
• 3 colheres de sopa de suco de limão
• Raspas de limão para decorar";

                            $modoprep = "**Preparo da Massa:**
1. Pré-aqueça o forno a 180°C e unte uma forma de bolo com manteiga e farinha.

2. Em uma tigela grande, bata os ovos com o açúcar até obter um creme claro e fofo (cerca de 5 minutos).

3. Adicione o óleo e o leite, misturando bem.

4. Acrescente as raspas de limão e o suco de limão, incorporando delicadamente.

5. Em outra tigela, peneire a farinha com o fermento.

6. Adicione os ingredientes secos à mistura líquida, mexendo suavemente até formar uma massa homogênea.

7. Despeje a massa na forma preparada e leve ao forno por 35-40 minutos, ou até que um palito inserido no centro saia limpo.

**Preparo da Calda:**
8. Enquanto o bolo assa, prepare a calda misturando o açúcar, suco de limão e água em uma panela pequena.

9. Leve ao fogo baixo até o açúcar dissolver completamente. Reserve.

**Finalização:**
10. Assim que o bolo sair do forno, ainda quente, faça furos com um palito por toda a superfície.

11. Despeje a calda sobre o bolo quente, permitindo que seja absorvida.

12. Deixe esfriar completamente antes de desenformar.

**Cobertura (opcional):**
13. Misture o açúcar de confeiteiro com o suco de limão até formar uma pasta lisa.

14. Cubra o bolo com a mistura e decore com raspas de limão.

15. Sirva em temperatura ambiente.";

                            $rendimento = "8-10 fatias";
                            $tempo_preparo = "1 hora e 30 minutos";
                            $imagem = "bolo-limao.jpg";
                            $status = "aprovada";
                            
                            // SQL de inserção
                            $sql_insert = "INSERT INTO receita (
                                usuario_id, categoria_id, titulo, descricao, ingredientes, 
                                modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                            
                            $stmt = $conn->prepare($sql_insert);
                            $stmt->bind_param("iissssssss", 
                                $usuario_id, $categoria_id, $titulo, $descricao, $ingredientes,
                                $modoprep, $rendimento, $tempo_preparo, $imagem, $status
                            );
                            
                            if ($stmt->execute()) {
                                $inseriu = true;
                                $receita_id = $conn->insert_id;
                            } else {
                                $erro = "Erro ao inserir receita: " . $conn->error;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
            
            // Buscar estatísticas atuais
            $sql_stats = "SELECT 
                (SELECT COUNT(*) FROM receita) as total_receitas,
                (SELECT COUNT(*) FROM receita WHERE categoria_id = (SELECT id FROM categoria WHERE nome = 'Doces')) as receitas_doces,
                (SELECT COUNT(*) FROM receita WHERE titulo LIKE '%limão%') as receitas_limao";
            $stats = $conn->query($sql_stats)->fetch_assoc();
            ?>
            
            <?php if ($inseriu): ?>
                <div class="alert alert-success">
                    <strong>🎉 Sucesso!</strong> A receita "Bolo de Limão Cremoso" foi inserida com sucesso!<br>
                    <strong>ID da receita:</strong> <?= $receita_id ?><br>
                    <strong>Inserida em:</strong> <?= date('d/m/Y H:i:s') ?>
                </div>
                
                <button onclick="window.open('/Story-Bytes-/pages/ver_receita.php?id=<?= $receita_id ?>', '_blank')" class="btn btn-success">
                    👀 Ver Receita no Site
                </button>
                <button onclick="window.open('/Story-Bytes-/pages/doces.php', '_blank')" class="btn">
                    🍰 Ver Categoria Doces
                </button>
                
            <?php elseif ($erro): ?>
                <div class="alert alert-danger">
                    <strong>❌ Erro!</strong> <?= $erro ?>
                </div>
            <?php endif; ?>
            
            <!-- Estatísticas atuais -->
            <div class="alert alert-info">
                <strong>📊 Estatísticas Atuais:</strong>
                <div class="stats">
                    <div class="stat-item">
                        <strong><?= $stats['total_receitas'] ?></strong>
                        Total de Receitas
                    </div>
                    <div class="stat-item">
                        <strong><?= $stats['receitas_doces'] ?></strong>
                        Receitas de Doces
                    </div>
                    <div class="stat-item">
                        <strong><?= $stats['receitas_limao'] ?></strong>
                        Receitas com Limão
                    </div>
                </div>
            </div>
            
            <!-- Preview da receita -->
            <div class="recipe-preview">
                <div class="recipe-title">🍋 Bolo de Limão Cremoso</div>
                
                <div class="recipe-section">
                    <h4>📝 Descrição:</h4>
                    <p>Um delicioso bolo de limão macio e suculento, perfeito para acompanhar um café da tarde. Com sabor cítrico marcante e textura fofa, este bolo é uma sobremesa irresistível que agrada toda a família.</p>
                </div>
                
                <div class="stats">
                    <div class="stat-item">
                        <strong>⏱️ Tempo</strong>
                        1h30min
                    </div>
                    <div class="stat-item">
                        <strong>🍽️ Rendimento</strong>
                        8-10 fatias
                    </div>
                    <div class="stat-item">
                        <strong>🏷️ Categoria</strong>
                        Doces
                    </div>
                </div>
                
                <div class="recipe-section">
                    <h4>🛒 Ingredientes:</h4>
                    <div class="ingredients">**Massa:**
• 3 ovos
• 1 xícara de açúcar
• 1/2 xícara de óleo
• 1 xícara de leite
• 2 xícaras de farinha de trigo
• 1 colher de sopa de fermento em pó
• Raspas de 2 limões
• 1/4 xícara de suco de limão

**Calda:**
• 1/2 xícara de açúcar
• 1/3 xícara de suco de limão
• 2 colheres de sopa de água

**Cobertura (opcional):**
• 1 xícara de açúcar de confeiteiro
• 3 colheres de sopa de suco de limão
• Raspas de limão para decorar</div>
                </div>
                
                <div class="recipe-section">
                    <h4>👩‍🍳 Modo de Preparo:</h4>
                    <div class="instructions">**Preparo da Massa:**
1. Pré-aqueça o forno a 180°C e unte uma forma de bolo com manteiga e farinha.
2. Em uma tigela grande, bata os ovos com o açúcar até obter um creme claro e fofo.
3. Adicione o óleo e o leite, misturando bem.
4. Acrescente as raspas de limão e o suco de limão.
5. Peneire a farinha com o fermento e adicione à mistura.
6. Asse por 35-40 minutos.

**Preparo da Calda:**
7. Misture açúcar, suco de limão e água.
8. Leve ao fogo baixo até dissolver.
9. Despeje sobre o bolo quente.

**Cobertura:**
10. Misture açúcar de confeiteiro com suco de limão.
11. Cubra o bolo e decore com raspas de limão.</div>
                </div>
            </div>
            
            <?php if (!$inseriu && !$erro): ?>
                <!-- Formulário de inserção -->
                <form method="POST" action="">
                    <input type="hidden" name="acao" value="inserir">
                    <button type="submit" class="btn" onclick="return confirm('🤔 Tem certeza que deseja inserir esta receita no banco de dados?')">
                        🍋 Inserir Receita de Bolo de Limão
                    </button>
                </form>
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <button onclick="window.open('/Story-Bytes-/', '_blank')" class="btn">
                    🏠 Voltar ao Site
                </button>
                <button onclick="window.open('/Story-Bytes-/alterar_categorias.php', '_blank')" class="btn">
                    🔧 Gerenciar Categorias
                </button>
            </div>
        </div>
    </div>
</body>
</html>