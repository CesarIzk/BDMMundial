<?php

session_start();
const BASE_PATH = __DIR__.'/../';

require BASE_PATH.'core/functions.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    require base_path("{$class}.php");
});

//require base_path('bootstrap.php');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// AÑADE ESTA LÍNEA PARA DEPURAR
die("La URI que ve el router es: " . $uri);

$router->route($uri, $method);