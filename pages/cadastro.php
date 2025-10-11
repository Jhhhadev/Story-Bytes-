<?php
$ACTIVE_PAGE = 'cadastro';
$PAGE_TITLE  = 'StoryBites — Cadastro';
$PAGE_DESC   = 'Doces caseiros com histórias e memórias afetivas.';
$PAGE_STYLES = [
                'css/login.css',
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
?>

<main class="formulario">
  <h2 class="titulo_login">Cadastro de Usuário</h2>
  <form class="box_form" action="pages/processa_cadastro.php" method="post">
    <label for="nome">Apelido:</label>
    <input type="text" name="nome" id="nome" required>

    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <input type="submit" value="Cadastrar">
  </form>
</main>


<?php require_once APP_ROOT . '/partials/_footer.php'; ?>