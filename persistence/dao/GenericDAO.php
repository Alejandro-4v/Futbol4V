<?php
declare(strict_types=1);

require_once 'PersistentManager.php';

abstract class GenericDAO
{

    protected PDO $conn;
    protected string $tableName;
    protected string $entityClass;
    protected string $primaryKey;

    public function __construct(string $tableName, string $entityClass, string $primaryKey)
    {
        $this->conn = PersistentManager::getInstance()->getConnection();
        $this->tableName = $tableName;
        $this->entityClass = $entityClass;
    }

    // Método abstracto para guardar una entidad (INSERT o UPDATE).
    abstract public function guardar(object $entity): void;

    // Obtiene todos los registros de una tabla y los mapea a objetos de la clase Entidad.
    public function obtenerTodos(): array
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY {$this->primaryKey}";

        $stmt = $this->conn->query($query);

        // Configura el modo de fetch para devolver un array y mapea a objetos de la clase que herede GenericDAO
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);

        /** @var array<object> $entities */
        $entities = $stmt->fetchAll();
        return $entities;
    }

    // No existe delete porque sería dead code en este proyecto, sino también se declararía aquí 

}