<?php 
/* partials/_header.php */ 
// Verificar se a sessão já foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se usuário está logado
$usuario_logado_header = isset($_SESSION['usuario_id']);
$nome_usuario_header = $usuario_logado_header ? $_SESSION['usuario_nome'] : '';
?>

<header class="menu" role="banner">  <!-- role indica que essa seção é o cabeçaho do site -->
    <nav class="menu__nav" aria-label="Menu Principal">
        <a href="/Story-Bytes-/">
            <img src="img/logo-story.png" alt="Logo StoryBites" width="90">            
        </a>
        
        <!-- Botão do menu hamburguer -->
        <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">
            <span class="material-symbols-outlined">menu</span>
        </button>


        <ul class="menu__links">
            <li><a href="/Story-Bytes-/" aria-current="page">INICIO</a></li> <!-- aria-current indica o inicio da pagina atual -->
            <li><a href="/Story-Bytes-/pages/doces.php">DOCES</a></li>
            <li><a href="/Story-Bytes-/pages/massas.php">MASSAS</a></li>
            <li><a href="/Story-Bytes-/pages/carnes.php">CARNES</a></li>
            <li><a href="/Story-Bytes-/pages/sopas.php">SOPAS</a></li>
            <li><a href="/Story-Bytes-/pages/lanches.php">LANCHES</a></li>
            <li><a href="/Story-Bytes-/pages/bebidas.php">BEBIDAS</a></li>
            
            <?php if ($usuario_logado_header): ?>
                <!-- Menu para usuários logados -->
                <li><a href="/Story-Bytes-/pages/perfil.php">PERFIL</a></li>
                <li style="position: relative;">
                    <div style="color: #2C1810; font-weight: bold; text-shadow: 1px 1px 2px rgba(255,255,255,0.5); margin-bottom: 2px;">
                        Olá, <?= htmlspecialchars($nome_usuario_header) ?>!
                    </div>
                    <div>
                        <a href="/Story-Bytes-/pages/logout.php" style="color: #8B4513; font-size: 0.85em; text-decoration: underline; font-weight: normal;">SAIR</a>
                    </div>
                </li>
            <?php else: ?>
                <!-- Menu para usuários não logados -->
                <li><a href="/Story-Bytes-/pages/login.php">ENTRAR</a></li>
                <li><a href="/Story-Bytes-/pages/cadastro.php">CADASTRAR</a></li>
            <?php endif; ?>
        </ul>

        <form class="search-form" action="#" method="GET" role="search" autocomplete="off">
            <input type="text" id="campo-busca" name="busca" placeholder="Buscar receitas..." aria-label="Buscar receitas">
            <button type="submit">PROCURAR</button> 
        </form>

    </nav>   
</header>

