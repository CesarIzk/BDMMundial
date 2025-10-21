<?php

// Autenticación (sin .php, solo el namespace)
$router->post('/login', 'Controles/Api/AuthController@login');
$router->post('/register', 'Controles/Api/AuthController@register');
$router->get('/logout', 'Controles/Api/AuthController@logout');

// Publicaciones
$router->get('/Post', 'controls/Post.php');
$router->get('/Post/crear', 'controls/CrearPublicacion.php');
$router->post('/Post/store', 'Controles/Api/PostController@store');
$router->post('/Post/{id}/like', 'Controles/Api/PostController@like');

// Rutas existentes
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');