<?php
declare(strict_types=1);

const CREDENTIALS_PATH = __DIR__ . '/conf/credentials.json';

// Empleo un singleton para la gestión de la conexión a la BD
// Lo he estructurado así para simular una práctica profesional cumpliendo con principios SOLID (y empleando PDO, que también me parecía interesnte )
class PersistentManager {
    private static ?PersistentManager $instance = null;

    // Conexión PDO que será compartida por ser la clase singleton
    private ?PDO $conn = null;

    // Constructor privado que lee el JSON y establece la conexión PDO
    private function __construct() {
        if (!file_exists(CREDENTIALS_PATH)) {
            throw new Exception("ERROR: El archivo de credenciales no se encuentra en la ruta esperada.");
        }
        
        $config = json_decode(file_get_contents(CREDENTIALS_PATH), true);

        // Verifico que se hayan cargado todas las credenciales necesarias
        if (empty($config['host']) || empty($config['dbname']) || !isset($config['user']) || !isset($config['password'])) {
            throw new Exception("ERROR: Las credenciales de BBDD están incompletas en el archivo JSON.");
        }

        // dsn para la conexión PDO
        /** @var string $dsn */
        $dsn = 'mysql:' . 'host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . ($config['charset'] ?? 'utf8mb4');
        
        // Opciones de PDO recomendadas para robustez
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzo excepciones en caso de error en la BD
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch por defecto como array asociativo (las PK son las claves del array)
            PDO::ATTR_EMULATE_PREPARES   => false, // Desactivo emulación de prepared statements para mayor seguridad (evitar posibles inyecciones SQL aunque nadie vaya a inyectar nada el XAMPP local...) Lo hago por simular una práctica profesional
        ];

        try {
            $this->conn = new PDO($dsn, $config['user'], $config['password'], $options);
        } catch (PDOException $e) {
            // Capturo el error de conexión y lanzo una excepción de la aplicación (aunque luego no gestione esta segunda excepción)
            throw new Exception("Error de Conexión a la BBDD: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Devuelvo la única instancia de PersistentManager (Singleton)
    public static function getInstance(): PersistentManager {
        if (self::$instance === null) {
            self::$instance = new PersistentManager();
        }
        return self::$instance;
    }

    // Devuelvo el objeto de conexión PDO
    public function getConnection(): PDO {
        if ($this->conn === null) {
            // En caso de que se intente obtener la conexión sin que se haya inicializado correctamente
            throw new Exception("La conexión PDO no está inicializada.");
        }
        return $this->conn;
    }

    // Métodos para prevenir la clonación y deserialización del Singleton (estos magic methods lanzarán un Fatal Error si se intentan usar)
    private function __clone() {}
    public function __wakeup() {}
}