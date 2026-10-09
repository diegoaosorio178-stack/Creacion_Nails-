<?php
session_start();

if (!defined('BASE_URL')) {
    // Detecta el directorio público relativo de la petición actual
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $baseUrl = rtrim($scriptDir, '/') . '/';
    define('BASE_URL', $baseUrl);
}

// Enrutador Frontal (Front Controller) para New CREACION NAILS
// Toda petición pasa por aquí: index.php?url=...

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$urlParts = explode('/', $url);

$controllerName = $urlParts[0];
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index';
$param = isset($urlParts[2]) ? $urlParts[2] : null;

// Mapa de Controladores
$controllers = [
    'usuario' => 'UsuarioController',
    'register' => 'RegisterController',
    'admin' => 'AdminController',
    'cliente' => 'CitaController',
    'manicurista' => 'ManicuristaController'
];

// Si es home, renderizamos la landing page
if ($controllerName === 'home') {
    require_once __DIR__ . '/views/home.php';
    exit;
}

// Cargar Controlador si existe en el mapa
if (array_key_exists($controllerName, $controllers)) {
    $className = $controllers[$controllerName];
    $controllerFile = __DIR__ . "/controllers/{$className}.php";
    
    // Extraer solo el nombre de la clase (ignorar subdirectorios como auth/)
    $realClassName = basename($className);
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $realClassName();
        
        // Ejecutar método si existe
        if (method_exists($controller, $methodName)) {
            $controller->$methodName($param);
        } else {
            // Método no encontrado, cargar dashboard por defecto
            if (method_exists($controller, 'dashboard')) {
                $controller->dashboard();
            } else {
                echo "<h1>Error 404: Método '{$methodName}' no encontrado en '{$realClassName}'</h1>";
            }
        }
    } else {
        echo "<h1>Error 404: Archivo del Controlador '{$className}.php' no encontrado.</h1>";
    }
} else {
    echo "<h1>Error 404: Ruta '{$controllerName}' no válida.</h1>";
}
