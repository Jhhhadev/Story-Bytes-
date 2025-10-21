<?php
$ACTIVE_PAGE = 'carnes';
$PAGE_TITLE  = 'StoryBites — Carnes';
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
        <img class="recipe-cover" src="img/bife-acebolado.jpeg" alt="Bife acebolado suculento">
        <div class="recipe-title">
          <h2 class="name-recipe">Bife Acebolado</h2>
          <div class="meta">
            <span><strong>Rendimento:</strong> 4 porções</span>
            <span><strong>Preparo:</strong> 30 min</span>
            <span><strong>Dificuldade:</strong> Fácil</span>
          </div>
        </div>
      </div>

    
      <div class="grid">
        <section class="ingredientes">
          <h3>Ingredientes</h3>
          <ul class="itens-ingredientes">
            <li>Tempere os bifes com sal</li>
            <li>4 bifes de contrafilé ou alcatra</li>
            <li>2 cebolas grandes cortadas em rodelas</li>
            <li>2 colheres (sopa) de óleo ou azeite</li>
            <li>1 colher (sopa) de manteiga</li>
            <li>2 dentes de alho picados</li>
            <li>Sal e pimenta-do-reino a gosto</li>
          </ul>
        </section>
        
        <section class="preparo">
          <h3>Modo de Preparo</h3>
          <ol class="itens-preparo">
            <li>Tempere os bifes com sal, pimenta e alho.</li>
            <li>Aqueça uma frigideira com óleo e grelhe os bifes até o ponto desejado. Retire e reserve.</li>
            <li>Na mesma frigideira, adicione a manteiga e refogue as cebolas até ficarem douradas.</li>
            <li>Volte os bifes para a frigideira, misture com a cebola e deixe apurar por 2 minutos.</li>
            <li>Sirva acompanhado de arroz branco, batata frita ou salada.</li>
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
        <img class="recipe-cover" src="img/frango-mostarda.jpeg" alt="Frango ao molho de mostarda">
        <div class="recipe-title">
          <h2 class="name-recipe">Frango ao Molho de Mostarda</h2>
          <div class="meta">
            <span><strong>Rendimento:</strong> 4 porções</span>
            <span><strong>Preparo:</strong> 35 min</span>
            <span><strong>Dificuldade:</strong> Fácil</span>
          </div>
        </div>
    </div>
    
    <div class="grid">
    
      <section class="ingredientes">
        <h3>Ingredientes</h3>
        <ul class="itens-ingredientes">
          <li>500 g de peito de frango em tiras ou cubos</li>
          <li>2 colheres (sopa) de azeite</li>
          <li>2 dentes de alho picados</li>
          <li>1 cebola média picada</li>
          <li>1 caixinha de creme de leite (200 ml)</li>
          <li>2 colheres (sopa) de mostarda</li>
          <li>Sal e pimenta-do-reino a gosto</li>
          <li>Salsinha picada para finalizar</li>
        </ul>
      </section>

      
      <section class="preparo">
        <h3>Modo de Preparo</h3>
        <ol class="itens-preparo">
          <li>Tempere o frango com sal e pimenta.</li>
          <li>Aqueça o azeite em uma panela e doure o frango. Reserve.</li>
          <li>Na mesma panela, refogue o alho e a cebola até dourarem.</li>
          <li>Adicione o frango de volta, junte a mostarda e mexa bem.</li>
          <li>Acrescente o creme de leite e deixe cozinhar por 5 minutos em fogo baixo.</li>
          <li>Finalize com salsinha picada e sirva com arroz branco ou batatas.</li>
        </ol>
      </section>
    </div>

      <!-- Comentário -->
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