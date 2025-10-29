<?php
declare(strict_types=1);

require_once __DIR__ . '/../persistence/dao/GenericDAO.php';
require_once __DIR__ . '/../persistence/dao/EquipoDAO.php';
require_once __DIR__ . '/../utils/RenderHelper.php';

$equipoDAO = new Equipo();

/** @var array<Equipo> $equipos */
$equipos = $equipoDAO->obtenerTodos();
$pageTitle = 'Listado de Equipos';

ob_start();
?>

<main>
    <div class="vertical-list">
        <?php
        // Con la plantilla item_equipo.php y el RenderHelper renderizo cada equipo
        foreach ($equipos as $equipo) {
            echo RenderHelper::render('item_equipo', $equipo);
        }
        ?>
    </div>
</main>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/../templates/layout.php';