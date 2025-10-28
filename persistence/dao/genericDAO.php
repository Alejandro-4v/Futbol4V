<?php
declare(strict_types=1);

require_once 'PersistentManager.php'; // Necesario para acceder a la conexión PDO

/**
 * Clase abstracta que sirve como base para todos los Data Access Objects (DAO).
 * Proporciona métodos CRUD genéricos (Create, Read, Update, Delete) para cualquier tabla.
 * Utiliza PDO para las sentencias preparadas y la tipificación estricta.
 */
abstract class GenericDAO {

    // --- PROPIEDADES ---

    // Conexión PDO, proporcionada por el PersistentManager
    protected PDO $conn;

    // Nombre de la tabla de la BBDD que maneja el DAO que hereda (ej: 'equipos', 'partidos')
    protected string $tableName;

    // Nombre de la clase Entidad (DTO) que mapea (ej: Equipo::class, Partido::class)
    protected string $entityClass;


    // --- CONSTRUCTOR ---

    /**
     * Constructor que establece la conexión y configura la tabla/clase.
     * @param string $tableName Nombre de la tabla BBDD.
     * @param string $entityClass Nombre de la clase Entidad (ej: 'Equipo' o 'Partido').
     */
    public function __construct(string $tableName, string $entityClass) {
        // Obtiene la conexión PDO única del PersistentManager (Singleton).
        $this->conn = PersistentManager::getInstance()->getConnection();
        // Asigna la tabla que maneja el DAO específico (hijo).
        $this->tableName = $tableName;
        // Asigna la clase Entidad para el mapeo automático de resultados.
        $this->entityClass = $entityClass;
    }


    // --- MÉTODOS ABSTRACTOS ---

    /**
     * Método abstracto para guardar una entidad (Insertar o Actualizar).
     * Obliga a las clases hijas a implementar su lógica de guardado/validación específica.
     * @param object $entity La instancia de la entidad a guardar.
     * @return bool True si la operación fue exitosa.
     */
    abstract public function guardar(object $entity): bool;


    // --- MÉTODOS GENÉRICOS DE LECTURA (READ) ---

    /**
     * Obtiene una entidad por su clave primaria (asume que la clave se llama 'id').
     * @param int $id ID de la entidad a buscar.
     * @return object|null La entidad o null si no se encuentra.
     */
    public function obtenerPorId(int $id): ?object {
        // Sentencia preparada para evitar inyección SQL
        $query = "SELECT * FROM {$this->tableName} WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Configura el modo de fetch para crear directamente un objeto de la clase Entidad
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
        
        $entity = $stmt->fetch();

        // PDO::fetch() devuelve false si no hay resultados.
        return $entity !== false ? $entity : null;
    }

    /**
     * Obtiene todos los registros de la tabla, ordenados por ID.
     * @return array<object> Lista de entidades.
     */
    public function obtenerTodos(): array {
        $query = "SELECT * FROM {$this->tableName} ORDER BY id ASC";
        
        $stmt = $this->conn->query($query);

        // Configura el modo de fetch para devolver un array de objetos de la clase Entidad
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
        
        // PDO::fetchAll() devuelve un array vacío si no hay resultados.
        /** @var array<object> $entities */
        $entities = $stmt->fetchAll();
        return $entities;
    }


    // --- MÉTODOS GENÉRICOS DE MANIPULACIÓN (DELETE) ---

    /**
     * Elimina un registro por su clave primaria (asume que la clave se llama 'id').
     * @param int $id ID del registro a eliminar.
     * @return bool True si se eliminó al menos una fila.
     */
    public function eliminar(int $id): bool {
        $query = "DELETE FROM {$this->tableName} WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // rowCount() devuelve el número de filas afectadas
        return $stmt->rowCount() > 0;
    }

}
