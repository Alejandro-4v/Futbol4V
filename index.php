<?php
declare(strict_types=1);

require_once __DIR__ . '/utils/SessionHelper.php';

$targetUrl = SessionHelper::getTargetPage();

header("Location: {$targetUrl}"); // Uso Location para redirigir
exit;

// Este fichero solo está para redirigir a la última página visitada o a inicio.php por defecto, nada más. Creo que era la solución más adecuada para guardar la última página visitada incluyendo inicio.php