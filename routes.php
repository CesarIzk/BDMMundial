<?php

// Publicaciones
$router->get('/Post', 'controles/Api/PostController@index');
$router->post('/Post/store', 'controles/Api/PostController@store')->only('auth');
$router->post('/Post/{id}/like', 'controles/Api/PostController@like')->only('auth');

// Autenticación
$router->post('/login', 'controles/Api/AuthController@login');
$router->post('/register', 'controles/Api/AuthController@register');
$router->get('/logout', 'controles/Api/AuthController@logout')->only('auth');

// Resto de rutas
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');