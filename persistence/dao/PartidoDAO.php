<?php
declare(strict_types=1);
require_once 'EquipoDAO.php';
// Añado EquipoDAO aquí para luego poder devolver nombres de equipos desde PartidoDAO

// Defino la entidad Partido, representa un registro de la tabla 'partidos' y que a la vez dará contenido a templates/item_partido.php
class Partido extends GenericDAO
{
    // Las propiedades se llaman igual que las columnas de la BD (PDO::FETCH_CLASS)
    protected ?int $id_partido = null;
    protected int $id_equipo_local;
    protected int $id_equipo_visitante;
    protected int $jornada;
    protected string $resultado;
    protected string $estadio_partido;

    // Propiedades adicionales para los nombres de los equipos (no están en la BD)
    protected string $nombre_local = '';
    protected string $nombre_visitante = '';

    public function __construct(
        int $id_equipo_local = 0,
        int $id_equipo_visitante = 0,
        int $jornada = 0,
        string $resultado = '',
        string $estadio_partido = '',
        ?int $id_partido = null // El ID puede ser null si es un nuevo partido
    ) {
        parent::__construct('partidos', Partido::class, 'id_partido');
        if ($id_equipo_local !== 0) {
            $this->id_equipo_local = $id_equipo_local;
        }
        if ($id_equipo_visitante !== 0) {
            $this->id_equipo_visitante = $id_equipo_visitante;
        }
        if ($jornada !== 0) {
            $this->jornada = $jornada;
        }
        if ($resultado !== '') {
            $this->resultado = $resultado;
        }
        if ($estadio_partido !== '') {
            $this->estadio_partido = $estadio_partido;
        }
        if ($id_partido !== null) {
            $this->id_partido = $id_partido;
        }
    }

    public function guardar(): void
    {
        if ($this->getIdPartido() === null) {
            // Lógica para insertar un nuevo partido
            $query = "INSERT INTO partidos (id_equipo_local, id_equipo_visitante, jornada, resultado, estadio_partido) 
                    VALUES (:id_equipo_local, :id_equipo_visitante, :jornada, :resultado, :estadio_partido)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id_equipo_local', $this->getIdEquipoLocal(), PDO::PARAM_INT);
            $stmt->bindValue(':id_equipo_visitante', $this->getIdEquipoVisitante(), PDO::PARAM_INT);
            $stmt->bindValue(':jornada', $this->getJornada(), PDO::PARAM_INT);
            $stmt->bindValue(':resultado', $this->getResultado());
            $stmt->bindValue(':estadio_partido', $this->getEstadioPartido());
            $stmt->execute();
            // Asignar el ID generado al objeto entidad
            $this->setIdPartido((int) $this->conn->lastInsertId());
        } else {
            // Lógica para actualizar un partido existente
            $query = "UPDATE partidos 
                        SET id_equipo_local = :id_equipo_local, 
                            id_equipo_visitante = :id_equipo_visitante, 
                            jornada = :jornada, 
                            resultado = :resultado, 
                            estadio_partido = :estadio_partido 
                        WHERE id_partido = :id_partido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id_equipo_local', $this->getIdEquipoLocal(), PDO::PARAM_INT);
            $stmt->bindValue(':id_equipo_visitante', $this->getIdEquipoVisitante(), PDO::PARAM_INT);
            $stmt->bindValue(':jornada', $this->getJornada(), PDO::PARAM_INT);
            $stmt->bindValue(':resultado', $this->getResultado());
            $stmt->bindValue(':estadio_partido', $this->getEstadioPartido());
            $stmt->bindValue(':id_partido', $this->getIdPartido(), PDO::PARAM_INT);
            $stmt->execute();
        }
        // Leí que estos bindValue también se pueden llevar a cabo con un execute() y un array asociativo de valores, pero así queda más claro
    }

    public function setNombreLocal(string $nombreLocal): void
    {
        $this->nombre_local = $nombreLocal;
    }

    public function setNombreVisitante(string $nombreVisitante): void
    {
        $this->nombre_visitante = $nombreVisitante;
    }

    public function getNombreLocal(): string
    {
        return $this->nombre_local;
    }

    public function getNombreVisitante(): string
    {
        return $this->nombre_visitante;
    }

    public function getIdPartido(): ?int
    {
        return $this->id_partido;
    }
    public function getIdEquipoLocal(): int
    {
        return $this->id_equipo_local;
    }
    public function getIdEquipoVisitante(): int
    {
        return $this->id_equipo_visitante;
    }
    public function getJornada(): int
    {
        return $this->jornada;
    }
    public function getResultado(): string
    {
        return $this->resultado;
    }
    public function getEstadioPartido(): string
    {
        return $this->estadio_partido;
    }

    // Setter de ID, el DAO lo usa para asignar el ID que genera la BD
    public function setIdPartido(int $id_partido): void
    {
        $this->id_partido = $id_partido;
    }
}
