<?php
declare(strict_types=1);

// ... requires ...
require_once __DIR__ . '/../persistence/dao/GenericDAO.php';
require_once __DIR__ . '/../persistence/dao/EquipoDAO.php';
require_once __DIR__ . '/../persistence/dao/PartidoDAO.php';
require_once __DIR__ . '/../utils/RenderHelper.php';


$partidoDAO = new Partido();
$equipoDAO = new Equipo();
$equipos = $equipoDAO->obtenerTodos();

/** @var array<Equipo> $equipos */
$nombresEquipos = [];
foreach ($equipos as $equipo) {
    // Asumimos que getNombre() y getIdEquipo() existen en Equipo
    $nombresEquipos[$equipo->getIdEquipo()] = $equipo->getNombre();
}

/** @var array<Partido> $partidos */
$partidos = $partidoDAO->obtenerTodos();

ob_start();
?>

<main>
    <div class="vertical-list">
        <?php
        foreach ($partidos as $partido) {

            $idLocal = $partido->getIdEquipoLocal();
            $idVisitante = $partido->getIdEquipoVisitante();

            $nombreLocal = $nombresEquipos[$idLocal] ?? 'Desconocido';
            $nombreVisitante = $nombresEquipos[$idVisitante] ?? 'Desconocido';

            $partido->setNombreLocal($nombreLocal);
            $partido->setNombreVisitante($nombreVisitante);

            echo RenderHelper::render('item_partido', $partido);
            echo RenderHelper::render('item_partido', $partido);
            echo RenderHelper::render('item_partido', $partido);
        }
        ?>
    </div>
</main>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/../templates/layout.php';