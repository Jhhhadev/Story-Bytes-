<?php
include('backend/conexao.php');

echo "<h1>🔍 Verificação das Receitas no Banco de Dados</h1>";

// Contar total de receitas
$sql_count = "SELECT COUNT(*) as total FROM receita";
$result_count = $conn->query($sql_count);
$total = $result_count->fetch_assoc()['total'];

echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center;'>";
echo "<h2 style='color: #1565c0; margin: 0;'>📊 Total de Receitas no Banco</h2>";
echo "<p style='font-size: 3rem; margin: 10px 0; color: #1565c0; font-weight: bold;'>{$total}</p>";
echo "</div>";

// Contar por categoria
echo "<h2>📋 Receitas por Categoria</h2>";
$sql_por_categoria = "SELECT c.nome as categoria, COUNT(r.id) as quantidade 
                      FROM categoria c 
                      LEFT JOIN receita r ON c.id = r.categoria_id 
                      GROUP BY c.id, c.nome 
                      ORDER BY c.nome";

$result_categorias = $conn->query($sql_por_categoria);

if ($result_categorias->num_rows > 0) {
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0;'>";
    
    while ($categoria = $result_categorias->fetch_assoc()) {
        $cor = $categoria['quantidade'] > 0 ? '#d4edda' : '#f8d7da';
        $texto_cor = $categoria['quantidade'] > 0 ? '#155724' : '#721c24';
        
        echo "<div style='background: {$cor}; padding: 15px; border-radius: 8px; text-align: center;'>";
        echo "<h3 style='color: {$texto_cor}; margin: 0 0 10px 0;'>{$categoria['categoria']}</h3>";
        echo "<p style='font-size: 2rem; margin: 0; color: {$texto_cor}; font-weight: bold;'>{$categoria['quantidade']}</p>";
        echo "</div>";
    }
    
    echo "</div>";
}

// Listar todas as receitas
echo "<h2>🍽️ Lista Completa de Receitas</h2>";
$sql_receitas = "SELECT r.id, r.titulo, c.nome as categoria, r.status_aprovacao, r.datacriacao, r.usuario_id 
                 FROM receita r 
                 LEFT JOIN categoria c ON r.categoria_id = c.id 
                 ORDER BY c.nome, r.titulo";

$result_receitas = $conn->query($sql_receitas);

if ($result_receitas->num_rows > 0) {
    echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
    echo "<thead style='background: #007bff; color: white;'>";
    echo "<tr>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>ID</th>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>Título</th>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>Categoria</th>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>Status</th>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>Usuário</th>";
    echo "<th style='padding: 12px; border: 1px solid #ddd;'>Data Criação</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $categoria_anterior = '';
    while ($receita = $result_receitas->fetch_assoc()) {
        $cor_linha = ($categoria_anterior !== $receita['categoria']) ? '#f8f9fa' : 'white';
        $categoria_anterior = $receita['categoria'];
        
        $status_cor = '';
        switch($receita['status_aprovacao']) {
            case 'aprovada':
                $status_cor = 'background: #d4edda; color: #155724;';
                break;
            case 'pendente':
                $status_cor = 'background: #fff3cd; color: #856404;';
                break;
            case 'rascunho':
                $status_cor = 'background: #e2e3e5; color: #495057;';
                break;
            default:
                $status_cor = 'background: #f8d7da; color: #721c24;';
        }
        
        echo "<tr style='background: {$cor_linha};'>";
        echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>{$receita['id']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'><strong>{$receita['titulo']}</strong></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$receita['categoria']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>";
        echo "<span style='padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; {$status_cor}'>";
        echo strtoupper($receita['status_aprovacao']);
        echo "</span></td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>{$receita['usuario_id']}</td>";
        echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>" . date('d/m/Y H:i', strtotime($receita['datacriacao'])) . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 8px; text-align: center;'>";
    echo "<h3 style='color: #721c24;'>❌ Nenhuma receita encontrada!</h3>";
    echo "<p>O banco de dados está vazio. Execute a importação primeiro.</p>";
    echo "</div>";
}

// Status final
if ($total > 0) {
    echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3 style='color: #0c5460;'>✅ Banco de Dados Populado!</h3>";
    echo "<p>Encontradas <strong>{$total} receitas</strong> no banco de dados.</p>";
    echo "<p>As receitas estão prontas para serem exibidas no site!</p>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3 style='color: #856404;'>⚠️ Banco Vazio</h3>";
    echo "<p>Não há receitas no banco de dados.</p>";
    echo "<p><a href='importar_receitas_completo.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🚀 Executar Importação</a></p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>🔗 Ações Rápidas:</h3>";
echo "<p>";
echo "<a href='importar_receitas_completo.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>📥 Importar Receitas</a>";
echo "<a href='pages/perfil.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>👤 Ver Perfil</a>";
echo "<a href='debug_dados.php' style='background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🔍 Debug Dados</a>";
echo "</p>";

$conn->close();
?>