<?php

use App\Core\Config;

$app = Config::app();
$pageTitle = $title ?? $app['name'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?= htmlspecialchars($app['asset_url'], ENT_QUOTES, 'UTF-8') ?>/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="<?= htmlspecialchars($app['base_url'], ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=view">
                <?= htmlspecialchars($app['name'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        </div>
    </nav>
    <main class="py-4">
        <div class="container">
            <?= $content ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= htmlspecialchars($app['asset_url'], ENT_QUOTES, 'UTF-8') ?>/assets/js/app.js"></script>
</body>
</html>
