**IMPORTANTE:** Para que las rutas funcionen correctamente, el proyecto debe estar en una carpeta llamada `futbol` en la raíz del servidor web (ej: `htdocs/futbol`) así como modificar las credenciales del servidor.

# Proyecto de Gestión de Liga de Fútbol

Este es un proyecto para la asignatura de Desarrollo de Aplicaciones Web, que simula la gestión de una liga de fútbol. La aplicación permite ver equipos, partidos, y añadir nuevos datos a la competición.

## Arquitectura

El proyecto sigue una arquitectura multicapa, separando la lógica de negocio, el acceso a datos y la presentación:

-   **Capa de Presentación (Vistas y Templates):** Ubicada en `templates/`, contiene los ficheros `.php` que renderizan el HTML. Se utiliza un layout principal (`layout.php`) y plantillas para elementos individuales como `item_equipo.php` y `item_partido.php`.
-   **Controladores (Lógica de Aplicación):** En la carpeta `app/`, se encuentran los scripts que gestionan las peticiones del usuario, interactúan con la capa de persistencia y seleccionan la vista a mostrar.
-   **Capa de Persistencia:** Localizada en `persistence/`, se encarga de la comunicación con la base de datos.
    -   **DAO (Data Access Object):** En `persistence/dao/`, se definen las clases `EquipoDAO.php` y `PartidoDAO.php` que mapean las tablas de la base de datos a objetos PHP. Se utiliza un `GenericDAO.php` para la lógica común y con métodos ya implementados para todas las tablas.
    -   **Gestor de Conexión:** `persistence/conf/PersistentManager.php` es un singleton que gestiona la conexión a la base de datos mediante PDO, leyendo las credenciales desde `credentials.json`.
-   **Base de Datos:** El script de inicialización `db/init.sql` crea las tablas `equipos` y `partidos` y las puebla con datos de ejemplo.

## Funcionalidades

### 1. Página de Inicio

El menú principal (`inicio.php`) ofrece dos opciones:

-   **Equipos:** Para gestionar la información de los equipos.
-   **Partidos:** Para consultar los resultados de las jornadas.

### 2. Gestión de Equipos

La sección de equipos (`app/equipos.php`) permite:

-   **Listar todos los equipos:** Muestra el nombre y el estadio de cada equipo.
-   **Añadir un nuevo equipo:** Un formulario permite introducir el nombre y el estadio de un nuevo equipo.
-   **Ver partidos de un equipo:** Al hacer clic en un equipo, se debería (funcionalidad a implementar) redirigir a una página que muestre todos los partidos de ese equipo.

### 3. Gestión de Partidos

La sección de partidos (`app/partidos.php`) permite:

-   **Listar partidos por jornada:** Muestra los resultados de los partidos (1, X, 2) y el estadio donde se jugaron. Un desplegable permite filtrar por jornada.
-   **Añadir un nuevo partido:** Un formulario permite registrar un nuevo partido, validando que los equipos no hayan jugado previamente en la misma jornada.

### 4. Gestión de Sesión

La aplicación recuerda la última página visitada por el usuario (que normalmente accederá por `index.php`):

-   Si un usuario no ha navegado por el sitio, su página de inicio será la de **inicio.php**.
-   Si ha consultado los partidos de un equipo, esa será su página de inicio en la siguiente visita.
-   Si ha consultado los equipos, esa será su página de inicio en la siguiente visita.


## Foco del Proyecto

**Lo más importante de este proyecto no es la interfaz de usuario**, sino el código que hay detrás, desde la capa de vista hasta la base de datos. Se valora la correcta implementación de la arquitectura, el acceso a datos y el seguimiento de buenas prácticas de desarrollo (es por eso que la interfaz es tan pobre).
