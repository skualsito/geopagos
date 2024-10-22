![Laravel](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

#### Challenge de Geopagos

# API de Torneos

Esta API proporciona endpoints para gestionar torneos y jugadores.

## Endpoints

### Simular y Guardar Torneo

-   **URL**: `/api/simular-torneo`
-   **Método**: POST
-   **Descripción**: Simula y guarda un torneo con los jugadores proporcionados.
-   **Cuerpo de la solicitud**: Array de objetos Jugador
-   **Respuesta exitosa**: Objeto Torneo

### Buscar Torneos por Fecha

-   **URL**: `/api/buscar-torneos-por-fecha`
-   **Método**: GET
-   **Descripción**: Busca torneos por una fecha específica.
-   **Parámetros de consulta**: `fecha` (formato: YYYY-MM-DD)
-   **Respuesta exitosa**: Array de objetos Torneo

### Buscar Torneos por Género

-   **URL**: `/api/buscar-torneos-por-genero`
-   **Método**: GET
-   **Descripción**: Busca torneos por género.
-   **Parámetros de consulta**: `genero`
-   **Respuesta exitosa**: Array de objetos Torneo

### Obtener Todos los Torneos

-   **URL**: `/api/obtener-todos-los-torneos`
-   **Método**: GET
-   **Descripción**: Obtiene una lista de todos los torneos.
-   **Respuesta exitosa**: Array de objetos Torneo

### Buscar Torneo por ID

-   **URL**: `/api/buscar-torneo-por-id`
-   **Método**: GET
-   **Descripción**: Busca un torneo específico por su ID.
-   **Parámetros de consulta**: `id`
-   **Respuesta exitosa**: Objeto Torneo

## Modelos

### Torneo

- id 
- fecha
- género
- jugadores
- resultados;

### Jugador

- id
- nombre
- género
- nivel

## Instalación

1. Clona el repositorio
2. Ejecuta `composer install`
3. Configura tu archivo `.env`
4. Ejecuta las migraciones con `php artisan migrate`
5. Inicia el servidor con `php artisan serve`
