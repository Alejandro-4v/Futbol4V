-- Desactivar la verificación de claves externas temporalmente para asegurar una carga limpia
SET
    FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas si ya existen para un entorno de inicialización limpio
DROP TABLE IF EXISTS partidos;

DROP TABLE IF EXISTS equipos;

CREATE TABLE
    equipos (
        id_equipo INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE, -- El nombre del equipo debe ser único
        estadio VARCHAR(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Especifico motor de almacenamiento (para versiones anteriores de MySQL) y CHARSET

CREATE TABLE
    partidos (
        id_partido INT AUTO_INCREMENT PRIMARY KEY,
        id_equipo_local INT NOT NULL,
        id_equipo_visitante INT NOT NULL,
        jornada INT NOT NULL,
        resultado ENUM ('1', 'X', '2') NOT NULL,
        estadio_partido VARCHAR(100) NOT NULL,
        -- Definición de las foreign key
        CONSTRAINT fk_partido_local FOREIGN KEY (id_equipo_local) REFERENCES equipos (id_equipo) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_partido_visitante FOREIGN KEY (id_equipo_visitante) REFERENCES equipos (id_equipo) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Especifico motor de almacenamiento (para versiones anteriores de MySQL) y CHARSET

INSERT INTO
    equipos (nombre, estadio)
VALUES
    ('Lions F.C.', 'The Den'),
    ('Eagles United', 'Eagle Nest Stadium'),
    ('Tigers City', 'Tiger Arena'),
    ('Bears Athletic', 'The Cave');

INSERT INTO
    partidos (
        id_equipo_local,
        id_equipo_visitante,
        jornada,
        resultado,
        estadio_partido
    )
VALUES
    (1, 2, 1, '1', 'The Den');

INSERT INTO
    partidos (
        id_equipo_local,
        id_equipo_visitante,
        jornada,
        resultado,
        estadio_partido
    )
VALUES
    (3, 4, 1, 'X', 'Tiger Arena');

INSERT INTO
    partidos (
        id_equipo_local,
        id_equipo_visitante,
        jornada,
        resultado,
        estadio_partido
    )
VALUES
    (4, 1, 2, '2', 'The Cave');

INSERT INTO
    partidos (
        id_equipo_local,
        id_equipo_visitante,
        jornada,
        resultado,
        estadio_partido
    )
VALUES
    (2, 3, 2, '1', 'Eagle Nest Stadium');

-- Reactivar la verificación de claves externas
SET
    FOREIGN_KEY_CHECKS = 1;