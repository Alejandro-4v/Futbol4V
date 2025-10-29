<?php
declare(strict_types=1);

class SessionHelper
{
    private const LAST_PAGE_KEY = 'last_visited_page'; // Guardo la última página visitada mediante una constante 

    private const INICIO_PAGE = '/futbol/app/inicio.php'; 

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Guardo la URL de la página actual en la sesión, incluyendo inicio.php. Considero que también es una página más a memorizar. De no quererlo, aquí se podría implementar lógica para excluir inicio.php.
    public static function saveCurrentPage(): void
    {
        self::startSession();
        
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Excluyo peticiones POST como la de los formularios
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $_SESSION[self::LAST_PAGE_KEY] = $uri;
        }
    }

    // Devuelvo la última página guardada o la página de inicio por defecto
    public static function getTargetPage(): string
    {
        self::startSession();
        // Si no existe, uso la página de inicio como valor por defecto
        return $_SESSION[self::LAST_PAGE_KEY] ?? self::INICIO_PAGE;
    }
}