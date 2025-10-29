<?php
declare(strict_types=1);

require_once __DIR__ . '/../persistence/dao/GenericDAO.php';
require_once __DIR__ . '/../persistence/dao/EquipoDAO.php';
require_once __DIR__ . '/../utils/RenderHelper.php';

$equipoDAO = new Equipo();

/** @var array<Equipo> $equipos */
$equipos = $equipoDAO->obtenerTodos();
$pageTitle = 'Listado de Equipos';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreEquipo = $_POST['nombreEquipo'];
    $estadio = $_POST['estadio'];

    $nuevoEquipo = new Equipo($nombreEquipo, $estadio);
    $nuevoEquipo->guardar();

    header('Location: equipos.php');
    exit;
}

ob_start();
?>

<main class="with-list-content">
    <div class="vertical-list">
        <?php
        // Con la plantilla item_equipo.php y el RenderHelper renderizo cada equipo
        foreach ($equipos as $equipo) {
            echo RenderHelper::render('item_equipo', $equipo);
        }
        ?>
    </div>

    <!-- Formulario para crear un equipo -->

    <form method="POST" action="equipos.php">
        <label for="nombreEquipo">Nombre del Equipo:</label>
        <input type="text" id="nombreEquipo" name="nombreEquipo" required>
        <label for="estadio">Estadio:</label>
        <input type="text" id="estadio" name="estadio" required>
        <button type="submit">Crear Equipo</button>
    </form>
</main>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/../templates/layout.php';