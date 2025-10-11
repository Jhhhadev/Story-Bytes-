<?php
$ACTIVE_PAGE = 'home';
$PAGE_TITLE  = 'StoryBites — Início';
$PAGE_DESC   = 'StoryBites reúne receitas de família e memórias deliciosas.';
$PAGE_STYLES = [
                'css/index.css',
                'css/carroussel.css',
];
require_once __DIR__ . '/config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
?>


<main>
    <section class="apresentacao">
        
    <!-- Adicione dentro da section .apresentacao ou antes dos cards -->
        <div class="carrossel" aria-roledescription="carousel">
            <div class="carrossel-imagens">
                <div class="slide"><img src="img/doces.jpg" alt="Doces variados, bolos e tortas" loading="lazy"></div>
                <div class="slide"><img src="img/massas.jpg" alt="Prato de macarrão com molho caseiro" loading="lazy"></div>
                <div class="slide"><img src="img/carnes.jpg" alt="Carne assada com legumes" loading="lazy"></div>
            </div>
            <button class="carrossel-btn prev" aria-label="Imagem anterior">&#10094;</button>
            <button class="carrossel-btn next" aria-label="Próxima imagem">&#10095;</button>
        </div>

        <h1 class="titulo-principal">Como tudo começou</h1>
        <p class="text-coment">
            O StoryBites nasceu com o intuito de reunir histórias através das receitas de famílias. 
            Cada receita guarda uma história, cada mordida remete a uma memória e traz sabores que transportam para outro momento. <br>
            Encontre receitas tradicionais, sofisticadas, fáceis, rápidas ou elaboradas, tudo em um só lugar. 
            O recheio de emoções transborda a cada mordida.
        </p>
    </section>

    <section class="conteudo">
        <div class="card">
            <img src="img/doces.jpg" alt="Bolo de chocolate decorado com frutas" loading="lazy">
            <div>
                <h2>Doces</h2>
                <p>Receitinhas de doces deliciosos.</p>
                <a href="pages/doces.php" class="button">Saiba Mais</a>
            </div>
        </div>

        <div class="card">
            <img src="img/massas.jpg" alt="Prato de macarrão com molho caseiro" loading="lazy">
            <div>
                <h2>Massas</h2>
                <p>Receitinhas de massas incríveis.</p>
                <a href="pages/massas.php" class="button">Saiba Mais</a>
            </div>
        </div>

        <div class="card">
            <img src="img/carnes.jpg" alt="Prato de carne assada com legumes" loading="lazy">
            <div>
                <h2>Carnes</h2>
                <p>Receitinhas de Carnes incríveis.</p>
                <a href="pages/carnes.php" class="button">Saiba Mais</a>
            </div>
        </div>

        <div class="card">
            <img src="img/sopas.png" alt="Coxinhas e salgadinhos variados" loading="lazy">
            <div>
                <h2>Sopas</h2>
                <p>Receitinhas de sopinhas saborosas.</p>
                <a href="pages/sopas.php" class="button">Saiba Mais</a>
            </div>            
        </div>
        
        <div class="card">
            <img src="img/lanches.jpg" alt="Coxinhas e salgadinhos variados" loading="lazy">
            <div>
                <h2>Lanches</h2>
                <p>Receitinhas de salgados saborosos.</p>
                <a href="pages/lanches.php" class="button">Saiba Mais</a>
            </div>            
        </div>

        <div class="card">
            <img src="img/bebidas.jpg" alt="Suco natural com frutas frescas" loading="lazy">
            <div>
                <h2>Bebidas</h2>
                <p>Receitinhas de bebidas refrescantes.</p>
                <a href="pages/bebidas.php" class="button">Saiba Mais</a>
            </div>
        </div>       
    </section>

</main>

<script src="script/script.js"></script>

<?php require_once APP_ROOT . '/partials/_footer.php'; ?>
