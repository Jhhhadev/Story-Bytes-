<?php
$ACTIVE_PAGE = 'bebidas';
$PAGE_TITLE  = 'StoryBites — Bebidas';
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
      <img class="recipe-cover" src="img/cha-hibisco.jpg" alt="Chá gelado de hibisco com limão">
      <div class="recipe-title">
        <h2 class="name-recipe">Chá Gelado de Hibisco com Limão</h2>
        <div class="meta">
          <span><strong>Rendimento:</strong> 1 jarra (1 litro)</span>
          <span><strong>Preparo:</strong> 15 min</span>
          <span><strong>Dificuldade:</strong> Fácil</span>
        </div>
      </div>
    </div>

  
    <div class="grid">      
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>2 colheres (sopa) de flores de hibisco secas (ou sachês de chá de hibisco)</li>
          <li>1 litro de água fervente</li>
          <li>Suco de 1 limão</li>
          <li>Mel ou adoçante a gosto</li>
          <li>Gelo a gosto</li>
        </ul>
      </section>

      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Coloque o hibisco na água fervente e deixe em infusão por 10 minutos.</li>
          <li>Coe, espere esfriar e adicione o suco de limão.</li>
          <li>Adoce se desejar e leve à geladeira.</li>
          <li>Sirva bem gelado com bastante gelo.</li>
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
      <img class="recipe-cover" src="img/agua-saborcitrico.jpg" alt="Água saborizada com frutas cítricas">
      <div class="recipe-title">
        <h2 class="name-recipe">Água Saborizada com Frutas Cítricas</h2>
        <div class="meta">
          <span><strong>Rendimento:</strong> 1 jarra (1,5 litro)</span>
          <span><strong>Preparo:</strong> 10 min</span>
          <span><strong>Dificuldade:</strong> Muito fácil</span>
        </div>
      </div>
    </div>
    
    <div class="grid">    
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>1,5 litro de água gelada</li>
          <li>1 laranja em rodelas</li>
          <li>1 limão siciliano em rodelas</li>
          <li>1 limão taiti em rodelas</li>
          <li>Folhas de hortelã fresca</li>
          <li>Gelo a gosto</li>
        </ul>
      </section>
      
      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Coloque a água em uma jarra grande.</li>
          <li>Adicione as rodelas de frutas cítricas e folhas de hortelã.</li>
          <li>Complete com gelo.</li>
          <li>Deixe descansar por 15 minutos na geladeira antes de servir.</li>
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