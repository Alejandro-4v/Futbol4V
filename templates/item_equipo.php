<div class="item-card">
    <p>
        ID: <?= htmlspecialchars((string) $equipo->getIdEquipo()) ?>
    </p>

    <h3>
        <?= htmlspecialchars($equipo->getNombre()) ?>
    </h3>

    <p>
        Estadio: <span><?= htmlspecialchars($equipo->getEstadio()) ?></span>
    </p>
</div>

<!-- Empleo ?= en vez de php al ser equivalente a un php echo -->