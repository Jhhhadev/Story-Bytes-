<!DOCTYPE html>
<html>
<head>
    <title>Teste de Caminhos</title>
    <style>
        img { max-width: 200px; margin: 10px; border: 2px solid blue; }
        .erro { border: 2px solid red !important; }
    </style>
</head>
<body>
    <h1>🧪 Teste de Caminhos de Imagem</h1>
    
    <h2>Localização atual:</h2>
    <p><?= $_SERVER['REQUEST_URI'] ?></p>
    <p>Document Root: <?= $_SERVER['DOCUMENT_ROOT'] ?></p>
    
    <h2>Teste 1: Caminho relativo ../img/receitas/</h2>
    <img src="../img/receitas/bolo-cenoura.jpeg" alt="Teste 1" 
         onerror="this.className='erro'; this.alt='ERRO: ' + this.src;">
    
    <h2>Teste 2: Caminho absoluto /Story-Bytes-/img/receitas/</h2>
    <img src="/Story-Bytes-/img/receitas/bolo-cenoura.jpeg" alt="Teste 2"
         onerror="this.className='erro'; this.alt='ERRO: ' + this.src;">
    
    <h2>Teste 3: Caminho direto sem ../</h2>
    <img src="img/receitas/bolo-cenoura.jpeg" alt="Teste 3"
         onerror="this.className='erro'; this.alt='ERRO: ' + this.src;">
    
    <h2>Verificação de arquivo:</h2>
    <?php
    $caminhos = [
        "../img/receitas/bolo-cenoura.jpeg",
        "img/receitas/bolo-cenoura.jpeg",
        "/xampp/htdocs/Story-Bytes-/img/receitas/bolo-cenoura.jpeg"
    ];
    
    foreach ($caminhos as $caminho) {
        echo "<p><strong>$caminho:</strong> " . (file_exists($caminho) ? "✅ EXISTE" : "❌ NÃO EXISTE") . "</p>";
    }
    ?>

    <h2>Listagem de arquivos:</h2>
    <?php
    $dir = "../img/receitas/";
    if (is_dir($dir)) {
        $arquivos = scandir($dir);
        echo "<ul>";
        foreach ($arquivos as $arquivo) {
            if ($arquivo != "." && $arquivo != "..") {
                echo "<li>$arquivo</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>❌ Diretório ../img/receitas/ não encontrado</p>";
    }
    ?>
</body>
</html>