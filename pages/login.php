
<?php
$ACTIVE_PAGE = 'login';
$PAGE_TITLE  = 'StoryBites — Login';
$PAGE_DESC   = 'Doces caseiros com histórias e memórias afetivas.';
$PAGE_STYLES = [
                'css/login.css',
]; // CSS específico desta página

require_once __DIR__ . '/../config.php';
require_once APP_ROOT . '/partials/_head.php';
require_once APP_ROOT . '/partials/_header.php';
?>

<!-- <?php include('../includes/header.php'); ?> -->

<main class="formulario">
  <h2 class="titulo_login">Login</h2>
  <form  class="box_form" action="pages/valida_login.php" method="post">
    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <input type="submit" value="Entrar">
  </form>
</main>

<!-- <?php include('../includes/footer.php'); ?> -->

<?php require_once APP_ROOT . '/partials/_footer.php'; ?>