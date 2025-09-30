<?php

// Rutas principales de la aplicación
$router->get('/', 'controls/inicio.php')->only('guest');                 // Página de inicio
$router->post('/login', 'controls/log/login.php')->only('guest');       // Procesa el inicio de sesión
$router->get('/logout', 'controls/log/logout.php')->only('auth');       // Cierra la sesión

// Rutas de las secciones del sitio
$router->get('/equipos', 'controls/equipos.php');                        // Muestra la vista de equipos
$router->get('/campeonatos', 'controls/campeonatos.php');               // Muestra la vista de campeonatos
$router->get('/tienda', 'controls/tienda.php');                         // Muestra la vista de la tienda
$router->get('/Post', 'controls/Post.php');                     // Muestra la vista de partidos
$router->get('/script', 'front/js/script.js');   
// Rutas de archivos estáticos
$router->get('/styles.css', 'front/styles.css');

// Dashboard de usuario autenticado
$router->get('/home', 'control/home.php')->only('auth');               // Vista principal para usuarios logueados
$router->get('/sign', 'control/sign.php');                             // Vista de registro/signin

// Rutas de la API (sin cambios, ya que no se solicitó modificarlas)
$router->get('/api/posts', 'control/Api/PostController@index');
$router->post('/api/posts/store', 'control/Api/PostController@store');