<?php /* partials/_header.php */ ?>

<header class="menu" role="banner">  <!-- role indica que essa seção é o cabeçaho do site -->
    <nav class="menu__nav" aria-label="Menu Principal">
        <a href="/Site-de-receitas>">
            <img src="img/logo-story.png" alt="Logo StoryBites" width="90">            
        </a>
        
        <!-- Botão do menu hamburguer -->
        <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">
            <span class="material-symbols-outlined">menu</span>
        </button>


        <ul class="menu__links">
            <li><a href="/Site-de-receitas" aria-current="page">INICIO</a></li> <!-- aria-current indica o inicio da pagina atual -->
            <li><a href="pages/doces.php">DOCES</a></li>
            <li><a href="pages/massas.php">MASSAS</a></li>
            <li><a href="pages/carnes.php">CARNES</a></li>
            <li><a href="pages/sopas.php">SOPAS</a></li>
            <li><a href="pages/lanches.php">LANCHES</a></li>
            <li><a href="pages/bebidas.php">BEBIDAS</a></li>
            <li><a href="pages/login.php">ENTRAR</a></li>
            <li><a href="pages/cadastro.php">CADASTRAR</a></li>
        </ul>

        <form class="search-form" action="#" method="GET" role="search" autocomplete="off">
            <input type="text" id="campo-busca" name="busca" placeholder="Buscar receitas..." aria-label="Buscar receitas">
            <button type="submit">PROCURAR</button> 
        </form>

    </nav>   
</header>

