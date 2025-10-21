<?php /* partials/_head.php */ ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <base href="http://localhost/Story-Bytes-/">

    <!-- Estilos globais -->
    <link rel="stylesheet" href="css/reset.css" media="screen">
    <link rel="stylesheet" href="css/variables.css" media="screen">
    <link rel="stylesheet" href="css/header.css" media="screen">
    <link rel="stylesheet" href="css/footer.css" media="screen">

    <!-- Estilos específicos da página -->
    <?php if (isset($PAGE_STYLES) && is_array($PAGE_STYLES)): ?>
        <?php foreach ($PAGE_STYLES as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Yanone+Kaffeesatz:wght@200..700&display=swap" rel="stylesheet">

    <title><?= isset($PAGE_TITLE) ? $PAGE_TITLE : 'StoryBites' ?></title>
    <meta name="description" content="<?= isset($PAGE_DESC) ? $PAGE_DESC : 'StoryBites reúne receitas de família e memórias deliciosas. Encontre doces, salgados, bebidas e massas que contam histórias.' ?>">
</head>
<body>

