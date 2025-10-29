<div class="item-card"> 
    <p>
        Jornada: <?= htmlspecialchars((string) $partido->getJornada()) ?>
    </p>

    <h3>
        <?= htmlspecialchars($partido->getNombreLocal()) ?> 
        <span>vs</span> 
        <?= htmlspecialchars($partido->getNombreVisitante()) ?>
    </h3>

    <p>
        Resultado: <strong><?= htmlspecialchars($partido->getResultado()) ?></strong>
    </p>
    
    <p>
        Estadio: <?= htmlspecialchars($partido->getEstadioPartido()) ?>
    </p>
</div>

<!-- Empleo ?= en vez de php al ser equivalente a un php echo -->