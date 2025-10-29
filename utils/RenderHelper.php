<?php
declare(strict_types=1);

// Con esta clase renderizo templates inyectándoles objetos como contexto de datos con el nombre de la clase en minúsculas. No es la mejor práctica pero en este caso cuadra siempre y cuando las clases y templates estén bien nombrados
class RenderHelper
{
    public static function render(string $templateName, object $data): string
    {
        $templatePath = __DIR__ . '/../templates/' . $templateName . '.php';

        if (!file_exists($templatePath)) {
            throw new Exception("Error: La plantilla '{$templateName}' no se encontró.");
        }

        // Creo el nombre de la variable dinámicamente ('equipo' o 'partido') con una variable de variable
        // (new ReflectionClass($data))->getShortName() obtiene 'Equipo' o 'Partido'
        $className = (new ReflectionClass($data))->getShortName(); 
        $varName = strtolower($className); 
        
        // Uso la variable de variable (doble dollar) para crear $equipo o $partido
        // Esto hace que la variable $equipo (o $partido) esté disponible dentro del require
        $$varName = $data;

        ob_start();
        
        // El template ahora puede acceder a la variable $equipo o $partido
        require $templatePath;
        
        return ob_get_clean();
    }
}