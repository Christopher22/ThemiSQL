<?php
require __DIR__ . '/Source/ThemiSQL.php';
$site = ThemiSQL\ThemiSQL::getSite();
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="normalize.css" />
    <link rel="stylesheet" href="skeleton.css" />
    <link rel="stylesheet" href="<?= $site->getCSS() ?>" />
    <title><?= $site->getTitle() ?></title>
    <?= $site->getHeader(); ?>
</head>
<body class="container">
    <header class="row">
        <div class="twelve columns">
            <h1><?= $site->getTitle() ?></h1>
        </div>
    </header>

    <?= $site->getContent() ?>
</body>
