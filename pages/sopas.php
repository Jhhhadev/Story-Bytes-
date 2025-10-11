<?php
$ACTIVE_PAGE = 'sopas';
$PAGE_TITLE  = 'StoryBites — Sopas';
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
    <img class="recipe-cover" src="img/sopa-madioquinha.jpeg" alt="Sopa cremosa de mandioquinha">
    <div class="recipe-title">
      <h2 class="name-recipe">Sopa Cremosa de Mandioquinha</h2>
      <div class="meta">
        <span><strong>Rendimento:</strong> 4 porções</span>
        <span><strong>Preparo:</strong> 45 min</span>
        <span><strong>Dificuldade:</strong> Fácil</span>
      </div>
    </div>
  </div>

  
  <div class="grid">
    <section class="ingredientes">
      <h3>Ingredientes</h3>
      <ul class="itens-ingredientes">
        <li>500 g de mandioquinha (batata-baroa) descascada e picada</li>
        <li>1 cebola média picada</li>
        <li>2 dentes de alho amassados</li>
        <li>1 litro de caldo de legumes ou frango</li>
        <li>1 colher (sopa) de manteiga</li>
        <li>100 ml de creme de leite (opcional, para mais cremosidade)</li>
        <li>Sal e pimenta-do-reino a gosto</li>
        <li>Cheiro-verde para finalizar</li>
      </ul>
    </section>

    <section class="preparo">
      <h3>Modo de Preparo</h3>
      <ol class="'itens-preparo">
        <li>Em uma panela, aqueça a manteiga e refogue a cebola e o alho até dourarem.</li>
        <li>Adicione a mandioquinha picada e o caldo de legumes. Deixe cozinhar por cerca de 25 minutos, até os pedaços ficarem macios.</li>
        <li>Leve tudo ao liquidificador e bata até formar um creme liso.</li>
        <li>Volte o creme para a panela, ajuste o sal e a pimenta, e adicione o creme de leite se desejar uma textura mais aveludada.</li>
        <li>Aqueça por mais 5 minutos e finalize com cheiro-verde.</li>
        <li>Sirva quente, acompanhada de torradas ou pão francês.</li>
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
    <img class="recipe-cover" src="img/sopa-legumes.jpeg" alt="Sopa de legumes caseira">
    <div class="recipe-title">
      <h2 class="name-recipe">Sopa de Legumes Caseira</h2>
      <div class="meta">
        <span><strong>Rendimento:</strong> 6 porções</span>
        <span><strong>Preparo:</strong> 50 min</span>
        <span><strong>Dificuldade:</strong> Fácil</span>
      </div>
    </div>
  </div>

  
  <div class="grid">
    <section class="ingredientes">
      <h3>Ingredientes</h3>
      <ul class="itens-ingredientes">
        <li>2 batatas médias em cubos</li>
        <li>2 cenouras fatiadas</li>
        <li>1 chuchu em cubos</li>
        <li>1 abobrinha em cubos</li>
        <li>1 tomate picado</li>
        <li>1 cebola picada</li>
        <li>2 dentes de alho amassados</li>
        <li>1,5 litro de água ou caldo de frango/legumes</li>
        <li>2 colheres (sopa) de óleo ou azeite</li>
        <li>Sal, pimenta e cheiro-verde a gosto</li>
      </ul>
    </section>
    
    <section class="preparo">
      <h3>Modo de Preparo</h3>
      <ol class="itens-preparo">
        <li>Em uma panela grande, aqueça o óleo e refogue a cebola e o alho até dourarem.</li>
        <li>Acrescente os legumes (batata, cenoura, chuchu, abobrinha e tomate) e refogue por alguns minutos.</li>
        <li>Adicione a água (ou caldo) e tempere com sal e pimenta.</li>
        <li>Cozinhe em fogo médio por cerca de 30 a 40 minutos, até os legumes ficarem macios.</li>
        <li>Acerte o tempero e finalize com cheiro-verde picado.</li>
        <li>Sirva quente, acompanhada de pão fresco ou torradas.</li>
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