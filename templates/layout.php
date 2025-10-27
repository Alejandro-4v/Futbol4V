<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'Futbol' ?></title>
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <?= $content ?>
</body>

</html>