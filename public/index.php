<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

const BASE_PATH = __DIR__.'/../';

require BASE_PATH.'Core/functions.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("{$class}.php");
});

// 1️⃣ Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// 2️⃣ Cargar variables de Railway en $_ENV
foreach ($_SERVER as $key => $value) {
    if (str_starts_with($key, 'CLOUDINARY_')) {
        $_ENV[$key] = $value;
    }
}

// 3️⃣ Inicializar tu app
require base_path('bootstrap.php');

$router = new \Core\Router();
$routes = require base_path('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
