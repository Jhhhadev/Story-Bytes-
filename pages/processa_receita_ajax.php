<?php
session_start();
header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado']);
    exit();
}

include('../backend/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $usuario_id = $_SESSION['usuario_id'];
        $titulo = $_POST['titulo'] ?? '';
        $categoria_id = $_POST['categoria_id'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $ingredientes = $_POST['ingredientes'] ?? '';
        $modo_preparo = $_POST['modoprep'] ?? '';
        $rendimento = $_POST['rendimento'] ?? '';
        $tempo_preparo = $_POST['tempo_preparo'] ?? '';
        $acao = $_POST['acao'] ?? 'aprovar';
        
        // Validar campos obrigatórios
        if (empty($titulo) || empty($categoria_id) || empty($descricao) || empty($ingredientes) || empty($modo_preparo)) {
            throw new Exception('Todos os campos obrigatórios devem ser preenchidos');
        }
        
        // Definir status sempre como rascunho (sem sistema de aprovação)
        $status_aprovacao = 'rascunho';
        
        // Processar upload de imagem (opcional)
        $imagem_nome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../img/receitas/';
            
            // Criar diretório se não existir
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $arquivo_temp = $_FILES['imagem']['tmp_name'];
            $nome_original = $_FILES['imagem']['name'];
            $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
            
            // Verificar se é uma imagem válida
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extensao, $extensoes_permitidas)) {
                throw new Exception('Formato de imagem não permitido. Use JPG, PNG ou GIF.');
            }
            
            // Verificar tamanho do arquivo (máximo 2MB)
            if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
                throw new Exception('Arquivo muito grande. Tamanho máximo: 2MB.');
            }
            
            // Gerar nome único para o arquivo
            $imagem_nome = time() . '_' . uniqid() . '.' . $extensao;
            $caminho_completo = $upload_dir . $imagem_nome;
            
            if (!move_uploaded_file($arquivo_temp, $caminho_completo)) {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }
        
        // Inserir receita no banco
        $sql = "INSERT INTO receita (usuario_id, titulo, categoria_id, descricao, ingredientes, modoprep, rendimento, tempo_preparo, imagem, status_aprovacao, datacriacao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isisssssss", 
            $usuario_id, 
            $titulo, 
            $categoria_id, 
            $descricao, 
            $ingredientes, 
            $modo_preparo, 
            $rendimento, 
            $tempo_preparo, 
            $imagem_nome, 
            $status_aprovacao
        );
        
        if ($stmt->execute()) {
            $receita_id = $conn->insert_id;
            $mensagem = 'Receita salva com sucesso!';
                
            echo json_encode([
                'success' => true, 
                'message' => $mensagem,
                'receita_id' => $receita_id,
                'acao' => $acao
            ]);
        } else {
            throw new Exception('Erro ao salvar receita no banco de dados');
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}

$conn->close();
?>