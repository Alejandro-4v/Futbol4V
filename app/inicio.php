<?php
declare(strict_types=1);

ob_start();
?>
<main>
    <a href="/futbol/app/equipos.php" class="index-card">
        Equipos
    </a>
    <a href="/futbol/app/partidos.php" class="index-card">
        Partidos
    </a>
</main>
<?php
/** @var string $pageTitle */
$pageTitle = 'Página Principal';

/** @var string $content */
$content = ob_get_clean();

require_once __DIR__ . '/../templates/layout.php';
?>