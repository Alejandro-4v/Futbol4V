<?php
declare(strict_types=1);

const CREDENTIALS_PATH = __DIR__ . '/conf/credentials.json';

class PersistentManager {
    // Empleo un singleton para la gestión de la conexión a la BD
    private static ?PersistentManager $instance = null;

    // Conexión PDO que será compartida
    private ?PDO $conn = null;

    // Constructor privado: Lee el JSON y establece la conexión PDO
    private function __construct() {
        if (!file_exists(CREDENTIALS_PATH)) {
            throw new Exception("ERROR: El archivo de credenciales no se encuentra en la ruta esperada.");
        }
        
        $config = json_decode(file_get_contents(CREDENTIALS_PATH), true);

        // Verificamos que se hayan cargado todas las credenciales necesarias
        if (empty($config['host']) || empty($config['dbname']) || !isset($config['user']) || !isset($config['password'])) {
            throw new Exception("ERROR: Las credenciales de BBDD están incompletas en el archivo JSON.");
        }

        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . ($config['charset'] ?? 'utf8mb4');
        
        // Opciones de PDO recomendadas para robustez
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, $config['user'], $config['password'], $options);
        } catch (PDOException $e) {
            // Capturamos el error de conexión y lanzamos una excepción de la aplicación
            throw new Exception("Error de Conexión a la BBDD: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Devuelve la única instancia de PersistentManager (Singleton).
     */
    public static function getInstance(): PersistentManager {
        if (self::$instance === null) {
            self::$instance = new PersistentManager();
        }
        return self::$instance;
    }

    /**
     * Obtiene el objeto de conexión PDO.
     * @return PDO La conexión activa.
     */
    public function getConnection(): PDO {
        if ($this->conn === null) {
            // En caso de que se intente obtener la conexión sin que se haya inicializado correctamente
            throw new Exception("La conexión PDO no está inicializada.");
        }
        return $this->conn;
    }

    // Métodos para prevenir la clonación y deserialización del Singleton
    private function __clone() {}
    public function __wakeup() {}
}
