<?php

// Autenticación
$router->post('/login', 'controles/Api/AuthController@login');
$router->post('/register', 'controles/Api/AuthController@register');
$router->get('/logout', 'controles/Api/AuthController@logout')->only('auth');

// Publicaciones
$router->get('/Post', 'controles/Api/PostController@index');
$router->post('/Post/store', 'controles/Api/PostController@store')->only('auth');

// Rutas existentes
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');

$router->get('/Post/crear', 'controls/CrearPublicacion.php');
$router->post('/Post/store', 'controles/Api/PostController.php@store');