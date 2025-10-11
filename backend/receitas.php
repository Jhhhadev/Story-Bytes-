<?php
include 'conexao.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    // LISTAR RECEITAS
    $sql = "SELECT * FROM receita ORDER BY datacriacao DESC";
    $result = $conn->query($sql);
    
    $receitas = [];
    while($row = $result->fetch_assoc()) {
        $receitas[] = $row;
    }
    
    echo json_encode($receitas);
}

else if ($metodo === 'POST') {
    // CADASTRAR RECEITA
    $dados = json_decode(file_get_contents('php://input'), true);
    
    $titulo = $conn->real_escape_string($dados['titulo']);
    $descricao = $conn->real_escape_string($dados['descricao']);
    $modoprep = $conn->real_escape_string($dados['modoprep']);
    $rendimento = $conn->real_escape_string($dados['rendimento']);
    $categoria_id = isset($dados['categoria_id']) ? $dados['categoria_id'] : 1;
    
    $sql = "INSERT INTO receita (titulo, descricao, modoprep, rendimento, categoria_id) 
            VALUES ('$titulo', '$descricao', '$modoprep', '$rendimento', '$categoria_id')";
    
    if ($conn->query($sql)) {
        echo json_encode(["mensagem" => "Receita cadastrada com sucesso!", "id" => $conn->insert_id]);
    } else {
        echo json_encode(["erro" => "Erro ao cadastrar receita: " . $conn->error]);
    }
}

$conn->close();
?> 
