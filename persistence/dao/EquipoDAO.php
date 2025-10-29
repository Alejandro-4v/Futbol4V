<?php
declare(strict_types=1);

// Defino la entidad Equipo, que representa un registro de la tabla 'equipos' y que a la vez dará contenido a templates/item_equipo.php


class Equipo extends GenericDAO
{
    // Las propiedades se llaman igual que las columnas de la BD (PDO::FETCH_CLASS)
    protected ?int $id_equipo = null;
    protected string $nombre;
    protected string $estadio;
    protected string $primaryKey = 'id_equipo';

    public function __construct(
        string $nombre = '',
        string $estadio = '',
        ?int $id_equipo = null
    ) {
        parent::__construct('equipos', Equipo::class, 'id_equipo');
        if ($nombre !== '') {
            $this->nombre = $nombre;
        }
        if ($estadio !== '') {
            $this->estadio = $estadio;
        }
        if ($id_equipo !== null) {
            $this->id_equipo = $id_equipo;
        }
    }

    public function guardar(): void
    {
        if ($this->getIdEquipo() === null) {
            // Lógica para insertar un nuevo equipo
            $query = "INSERT INTO equipos (nombre, estadio) VALUES (:nombre, :estadio)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nombre', $this->getNombre());
            $stmt->bindValue(':estadio', $this->getEstadio());
            $stmt->execute();
            // Asignar el ID generado al objeto entidad
            $this->setIdEquipo((int) $this->conn->lastInsertId());
        } else {
            // Lógica para actualizar un equipo existente
            $query = "UPDATE equipos SET nombre = :nombre, estadio = :estadio WHERE nombre = :nombre";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nombre', $this->getNombre());
            $stmt->bindValue(':estadio', $this->getEstadio());
            $stmt->bindValue(':id_equipo', $this->getIdEquipo(), PDO::PARAM_INT);
            $stmt->execute();
        }
        // Leí que estos bindValue también se pueden llevar a cabo con un execute() y un array asociativo de valores, pero así queda más claro
    }

    public function getIdEquipo(): ?int
    {
        return $this->id_equipo;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getEstadio(): string
    {
        return $this->estadio;
    }

    // Este setter de ID el DAO lo usa para asignar el ID que genera la BD
    public function setIdEquipo(int $id_equipo): void
    {
        $this->id_equipo = $id_equipo;
    }
}
