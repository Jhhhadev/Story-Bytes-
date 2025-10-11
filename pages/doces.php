
<?php
$ACTIVE_PAGE = 'doces';
$PAGE_TITLE  = 'StoryBites — Doces';
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
          <img class="recipe-cover" src="img/bolo-cenoura.jpeg" alt="Bolo de Cenoura">
          <div class="recipe-title">
            <h2 class="name-recipe">Bolo de Cenoura com Cobertura de Chocolate</h2>
            <div class="meta">
              <span><strong>Rendimento:</strong> <span>10 porções</span></span>
              <span><strong>Preparo:</strong> <time>60 min</time></span>
              <span><strong>Dificuldade:</strong> Fácil</span>
            </div>
          </div>
        </div>

        <div class="grid">
          <section class="ingredientes">
            <h3>Ingredientes</h3>
            <ul class="itens-ingredientes">
              <li>3 cenouras médias descascadas e picadas</li>
              <li>3 ovos</li>
              <li>1 xícara de óleo</li>
              <li>2 xícaras de açúcar</li>
              <li>2 e 1/2 xícaras de farinha de trigo</li>
              <li>1 colher (sopa) de fermento químico</li>
            </ul>
            
            <h4 class="ingrediente-cobertura">Cobertura</h4>
            <ul class="itens-ingredientes">
              <li>4 colheres (sopa) de cacau em pó</li>
              <li>1/2 xícara de leite</li>
              <li>2 colheres (sopa) de manteiga</li>
              <li>1 xícara de açúcar</li>
            </ul>
          </section>

          <section class="preparo">
            <h3>Modo de Preparo</h3>
            <ol class="itens-preparo">
              <li>Bata no liquidificador as cenouras, ovos e óleo até ficar homogêneo.</li>
              <li>Em uma tigela, misture o creme com o açúcar e a farinha peneirada.</li>
              <li>Incorpore o fermento delicadamente e leve ao forno pré-aquecido (180 °C) por 35–45 min.</li>
              <li><strong>Cobertura:</strong> leve tudo ao fogo baixo, mexendo, até engrossar. Espalhe sobre o bolo morno.</li>
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
            <img class="recipe-cover" src="img/pudim.jpeg" alt="Pudim de Leite Condensado">
            <div class="recipe-title">
              <h2 class="name-recipe">Pudim de Leite Condensado</h2>
              <div class="meta">
                <span><strong>Rendimento:</strong> 12 porções</span>
                <span><strong>Preparo:</strong> <time datetime="PT90M">90 min</time></span>
                <span><strong>Dificuldade:</strong> Médio</span>
              </div>
            </div>
          </div>
      

          <div class="grid">
            <section class="ingredientes">
              <h3>Ingredientes</h3>
              <ul class="itens-ingredientes">
                <li>1 lata de leite condensado</li>
                <li>2 medidas (da lata) de leite</li>
                <li>3 ovos</li>
                <li>1 xícara de açúcar (para a calda)</li>
              </ul>
            </section>

            <section class="preparo">
              <h3>Modo de Preparo</h3>
              <ol class="itens-preparo">
                <li>Faça a calda: derreta o açúcar até dourar e caramelize uma forma.</li>
                <li>Bata no liquidificador o leite condensado, o leite e os ovos.</li>
                <li>Despeje a mistura na forma caramelizada.</li>
                <li>Cubra com papel alumínio e leve ao forno em banho-maria por 1h15.</li>
                <li>Deixe esfriar e desenforme.</li>
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
