<?php

// Autenticación (sin .php, solo el namespace)
$router->post('/login', 'controles/Api/AuthController@login');
$router->post('/register', 'controles/Api/AuthController@register');
$router->get('/logout', 'controles/Api/AuthController@logout');

// Publicaciones
$router->get('/Post', 'controls/Post.php');
$router->get('/Post/crear', 'controls/CrearPublicacion.php');
$router->post('/Post/store', 'controles/Api/PostController@store');
$router->post('/Post/{id}/like', 'controles/Api/PostController@like');

// Rutas existentes
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');