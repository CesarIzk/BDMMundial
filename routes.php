<?php

// Autenticación
$router->post('/login', 'Controles/Api/AuthController@login');
$router->post('/register', 'Controles/Api/AuthController@register');
$router->get('/logout', 'Controles/Api/AuthController@logout');

// Publicaciones
$router->get('/Post', 'Controles/Api/PostController@index');
$router->get('/Post/crear', 'controls/CrearPublicacion.php')->only('auth');
$router->post('/Post/store', 'Controles/Api/PostController@store')->only('auth');
$router->post('/Post/like', 'Controles/Api/PostController@like')->only('auth');

// Rutas existentes
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');
$router->get('/publicaciones', 'controls/Post.php');