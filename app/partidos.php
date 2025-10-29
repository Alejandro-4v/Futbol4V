<?php
declare(strict_types=1);

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
    $nombresEquipos[$equipo->getIdEquipo()] = $equipo->getNombre();
}

/** @var array<Partido> $partidos */
$partidos = $partidoDAO->obtenerTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_equipo_local = (int) $_POST['id_equipo_local'];
    $id_equipo_visitante = (int) $_POST['id_equipo_visitante'];
    $jornada = (int) $_POST['jornada'];
    $resultado = $_POST['resultado'];
    $estadio_partido = $_POST['estadio_partido'];

    $nuevoPartido = new Partido(
        $id_equipo_local,
        $id_equipo_visitante,
        $jornada,
        $resultado,
        $estadio_partido
    );
    $nuevoPartido->guardar();

    header('Location: partidos.php');
    exit;
}

/** @var array<Equipo> $nombresEquipos */
$nombresEquipos = [];
foreach ($equipos as $equipo) {
    // Mapear ID -> Nombre para el renderizado de la lista
    $nombresEquipos[$equipo->getIdEquipo()] = $equipo->getNombre();
}

/** @var array<Partido> $partidos */
$partidos = $partidoDAO->obtenerTodos();

ob_start();
?>

<main class="with-list-content">
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
        }
        ?>
    </div>

    <!-- Formulario para crear un partido -->
    <form method="POST" action="partidos.php">
        <h3>Crear Nuevo Partido</h3>

        <label for="jornada">Jornada:</label>
        <input type="number" id="jornada" name="jornada" required min="1" value="1">

        <label for="estadio_partido">Estadio:</label>
        <input type="text" id="estadio_partido" name="estadio_partido" required>

        <label for="resultado">Resultado (tipo quiniela):</label>
        <input type="text" id="resultado" name="resultado" value="0" required>

        <div style="display: flex; gap: 20px;">
            <div>
                <label for="id_equipo_local">Equipo Local:</label>
                <select id="id_equipo_local" name="id_equipo_local" required>
                    <option value="">-- Seleccionar Local --</option>
                    <?php foreach ($equipos as $equipo): ?>
                        <option value="<?= htmlspecialchars((string) $equipo->getIdEquipo()) ?>">
                            <?= htmlspecialchars($equipo->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="id_equipo_visitante">Equipo Visitante:</label>
                <select id="id_equipo_visitante" name="id_equipo_visitante" required>
                    <option value="">-- Seleccionar Visitante --</option>
                    <?php foreach ($equipos as $equipo): ?>
                        <option value="<?= htmlspecialchars((string) $equipo->getIdEquipo()) ?>">
                            <?= htmlspecialchars($equipo->getNombre()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit">Crear Partido</button>
    </form>

    <!-- Con este script evito que se puedan seleccionar el mismo equipo a la vez en vez de tener que validar en otro lado -->
    <script>
        const localSelect = document.getElementById('id_equipo_local');
        const visitanteSelect = document.getElementById('id_equipo_visitante');
        const visitanteOptions = visitanteSelect.querySelectorAll('option');

        function actualizarVisitante() {
            const idLocalSeleccionado = localSelect.value;

            visitanteOptions.forEach(option => {
                // Habilito todas las opciones primero
                option.disabled = false;
            });

            if (idLocalSeleccionado) {
                // Deshabilito la opción local en el visitante
                visitanteOptions.forEach(option => {
                    if (option.value === idLocalSeleccionado) {
                        option.disabled = true;
                    }
                });

                // Si el equipo visitante seleccionado es el mismo que el local, lo deselecciono
                if (visitanteSelect.value === idLocalSeleccionado) {
                    visitanteSelect.value = '';
                }
            }
        }

        // Ejecuto al cargar y cada vez que cambia el local
        localSelect.addEventListener('change', actualizarVisitante);
    </script>

</main>

<?php
$content = ob_get_clean();

require_once __DIR__ . '/../templates/layout.php';