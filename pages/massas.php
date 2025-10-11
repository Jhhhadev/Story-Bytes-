<?php
$ACTIVE_PAGE = 'massas';
$PAGE_TITLE  = 'StoryBites — Massas';
$PAGE_DESC   = 'Doces caseiros com histórias e memórias afetivas.';
$PAGE_STYLES = [
                'css/card-receitas.css',
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
?>

<main class="container">
 
  <article class="card recipe">
    <div class="recipe-header">
      <img class="recipe-cover" src="img/spagueti-bolognese.jpeg" alt="Espaguete à Bolonhesa">
      <div class="recipe-title">
        <h2 class="name-recipe">Espaguete à Bolonhesa</h2>
        <div class="meta">
          <span><strong>Rendimento:</strong> 5 porções</span>
          <span><strong>Preparo:</strong> 40 min</span>
          <span><strong>Dificuldade:</strong> Fácil</span>
        </div>
      </div>
    </div>

    <div class="grid">
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>500 g de espaguete</li>
          <li>400 g de carne moída</li>
          <li>1 lata de molho de tomate</li>
          <li>2 tomates picados</li>
          <li>1 cebola picada</li>
          <li>2 dentes de alho picados</li>
          <li>Sal, pimenta e azeite a gosto</li>
          <li>Queijo parmesão ralado</li>
        </ul>
      </section>

      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Cozinhe o espaguete até ficar al dente. Escorra e reserve.</li>
          <li>Refogue cebola e alho no azeite.</li>
          <li>Adicione a carne moída e deixe dourar.</li>
          <li>Junte tomates, molho e temperos. Cozinhe por 15 min.</li>
          <li>Misture com a massa e finalize com queijo ralado.</li>
        </ol>
      </section>
    </div>

    
    <section class="comentarios">
        <h3>Comentários</h3>
        <form class="formulario" action="#" method="POST">
          <textarea placeholder="Deixe seu comentário..." required></textarea>
          <button type="submit">Enviar</button>
        </form>
        <ul class="comentario-lista">
          <li><strong>Ana:</strong> Fiz e ficou maravilhoso!</li>
          <li><strong>Carlos:</strong> Adicionei coco ralado e ficou top!</li>
        </ul>
    </section>
  </article>

  
  <article class="card recipe">
      <div class="recipe-header">
        <img class="recipe-cover" src="img/spagueti-carbonara.jpeg" alt="Macarrão à Carbonara">
        <div class="recipe-title">
          <h2 class="name-recipe">Macarrão à Carbonara</h2>
          <div class="meta">
            <span><strong>Rendimento:</strong> 4 porções</span>
            <span><strong>Preparo:</strong> 25 min</span>
            <span><strong>Dificuldade:</strong> Fácil</span>
          </div>
        </div>
      </div>

  
      <div class="grid">
          <section class="ingredientes">
            <h3>Ingredientes</h3>
            <ul class="itens-ingredientes">
              <li>400 g de espaguete</li>
              <li>150 g de bacon em cubos (ou pancetta)</li>
              <li>3 ovos inteiros + 1 gema extra</li>
              <li>100 g de queijo parmesão ralado</li>
              <li>Sal e pimenta-do-reino a gosto</li>
            </ul>
          </section>

          <section class="preparo">
            <h3>Modo de Preparo</h3>
            <ol class="itens-preparo">
              <li>Cozinhe o espaguete em água com sal até ficar al dente. Reserve 1/2 xícara da água do cozimento.</li>
              <li>Frite o bacon/pancetta até dourar e ficar crocante.</li>
              <li>Em uma tigela, bata os ovos e a gema extra. Misture o queijo e tempere com pimenta.</li>
              <li>Escorra o macarrão e coloque-o de volta na panela quente.</li>
              <li>Junte o bacon e misture bem.</li>
              <li>Fora do fogo, adicione a mistura de ovos e queijo, mexendo rápido. Se necessário, acrescente a água do cozimento para deixar cremoso.</li>
              <li>Finalize com mais queijo e pimenta a gosto.</li>
            </ol>
          </section>
      </div>

    
      <section class="comentarios">
          <h3>Comentários</h3>
          <form class="formulario" action="#" method="POST">
            <textarea placeholder="Deixe seu comentário..." required></textarea>
            <button type="submit">Enviar</button>
          </form>
          <ul class="comentario-lista">
            <li><strong>Ana:</strong> Fiz e ficou maravilhoso!</li>
            <li><strong>Carlos:</strong> Adicionei coco ralado e ficou top!</li>
          </ul>
      </section>
  </article>

</main>


<?php require_once APP_ROOT . '/partials/_footer.php'; ?>