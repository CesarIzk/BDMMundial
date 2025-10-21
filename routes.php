<?php

// Autenticación
$router->post('/login', 'Controles/Api/AuthController@login');
$router->post('/register', 'Controles/Api/AuthController@register');
$router->get('/logout', 'Controles/Api/AuthController@logout');

// Publicaciones
$router->get('/Post', 'Controles/Api/PostController@index');
$router->get('/Post/crear', 'controls/CrearPublicacion.php')->only('auth');
$router->post('/Post/store', 'Controles/Api/PostController@store')->only('auth');
$router->post('/Post/like', 'Controles/Api/PostController@like');

// Admin - Gestión de usuarios
$router->get('/admin/usuarios', 'Controles/Api/AdminController@users')->only('admin');
$router->post('/admin/usuario/{id}/baja', 'Controles/Api/AdminController@deactivateUser')->only('admin');
$router->post('/admin/usuario/{id}/activar', 'Controles/Api/AdminController@activateUser')->only('admin');

// Perfil
$router->get('/perfil/show', 'Controles/Api/PerfilController@show')->only('auth');
$router->post('/perfil/update', 'Controles/Api/PerfilController@update')->only('auth');
$router->post('/perfil/cambiar-contrasena', 'Controles/Api/PerfilController@changePassword')->only('auth');
$router->post('/perfil/actualizar-avatar', 'Controles/Api/PerfilController@updateAvatar')->only('auth');
$router->post('/perfil/desactivar', 'Controles/Api/PerfilController@deactivate')->only('auth');
$router->get('/configuracion', 'Controles/Api/PerfilController@index')->only('auth');

// --- RUTAS DE VISTAS ---
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');
$router->get('/publicaciones', 'controls/Post.php');
$router->get('/perfil', 'controls/perfil.php')->only('auth');

// Admin - Gestión de usuarios
$router->get('/admin/usuarios', 'Controles/Api/AdminController@users')->only('admin');
$router->post('/admin/usuario/{id}/baja', 'Controles/Api/AdminController@deactivateUser')->only('admin');
$router->post('/admin/usuario/{id}/activar', 'Controles/Api/AdminController@activateUser')->only('admin');