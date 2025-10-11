<?php
// Caminho absoluto do projeto
define('APP_ROOT', __DIR__);

// URL base (ajuste se o projeto mudar de pasta/domínio)
define('BASE_URL', '/Site-de-receitas/');

// Página ativa no menu (cada página define a sua antes de incluir o header)
if (!isset($ACTIVE_PAGE)) { $ACTIVE_PAGE = ''; } // 'home', 'doces', ...

// Metadados padrão (cada página pode sobrescrever)
if (!isset($PAGE_TITLE)) { $PAGE_TITLE = 'StoryBites'; }
if (!isset($PAGE_DESC)) {
  $PAGE_DESC = 'StoryBites reúne receitas de família e memórias deliciosas. Encontre doces, salgados, bebidas e massas que contam histórias.';
}

// CSS específico por página (opcional)
if (!isset($PAGE_STYLES)) { $PAGE_STYLES = []; } // ex.: ['css/index.css', 'css/card-receitas.css']
