<?php
$ACTIVE_PAGE = 'lanches';
$PAGE_TITLE  = 'StoryBites — Lanches';
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
        <img class="recipe-cover" src="img/sanduiche-frango.jpeg" alt="Sanduíche natural de frango">
        <div class="recipe-title">
          <h2 class="name-recipe">Sanduíche Natural de Frango</h2>
          <div class="meta">
            <span><strong>Rendimento:</strong> 2 porções</span>
            <span><strong>Preparo:</strong> 15 min</span>
            <span><strong>Dificuldade:</strong> Fácil</span>
          </div>
        </div>
      </div>

    
    <div class="grid">  
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>4 fatias de pão integral</li>
          <li>1 peito de frango cozido e desfiado</li>
          <li>2 colheres (sopa) de iogurte natural</li>
          <li>1 cenoura pequena ralada</li>
          <li>Folhas de alface</li>
          <li>Sal e pimenta a gosto</li>
        </ul>
      </section>
      
      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Misture o frango desfiado com o iogurte, a cenoura ralada, sal e pimenta.</li>
          <li>Monte o sanduíche com o recheio e folhas de alface.</li>
          <li>Sirva fresco e geladinho.</li>
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
      <img class="recipe-cover" src="img/sanduiche-atum.jpeg" alt="Wrap integral de atum com salada">
      <div class="recipe-title">
        <h2 class="name-recipe">Wrap Integral de Atum com Salada</h2>
        <div class="meta">
          <span><strong>Rendimento:</strong> 2 porções</span>
          <span><strong>Preparo:</strong> 10 min</span>
          <span><strong>Dificuldade:</strong> Fácil</span>
        </div>
      </div>
    </div>
    
    <div class="grid">
      
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>2 tortilhas integrais</li>
          <li>1 lata de atum (escorrido)</li>
          <li>2 colheres (sopa) de cream cheese light</li>
          <li>Folhas de alface e rúcula</li>
          <li>1 tomate em fatias</li>
          <li>1/2 cenoura ralada</li>
          <li>Orégano a gosto</li>
        </ul>
      </section>
      
      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Misture o atum com o cream cheese.</li>
          <li>Abra a tortilha, coloque alface, rúcula, tomate e cenoura.</li>
          <li>Espalhe o creme de atum e enrole o wrap.</li>
          <li>Corte ao meio e sirva.</li>
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