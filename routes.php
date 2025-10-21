<?php

// ========== AUTENTICACIÓN ==========
$router->post('/login', 'Controles/Api/AuthController@login');
$router->post('/register', 'Controles/Api/AuthController@register');
$router->get('/logout', 'Controles/Api/AuthController@logout');

// ========== PUBLICACIONES ==========
$router->get('/Post', 'Controles/Api/PostController@index');
$router->get('/Post/crear', 'controls/CrearPublicacion.php')->only('auth');
$router->post('/Post/store', 'Controles/Api/PostController@store')->only('auth');
$router->post('/Post/like', 'Controles/Api/PostController@like');
$router->get('/Post/{id}', 'Controles/Api/PostController@show'); // Vista pública de un post

// ========== PERFIL ==========
$router->get('/perfil', 'controls/perfil.php')->only('auth');
$router->get('/perfil/show', 'Controles/Api/PerfilController@show')->only('auth');
$router->post('/perfil/update', 'Controles/Api/PerfilController@update')->only('auth');
$router->post('/perfil/cambiar-contrasena', 'Controles/Api/PerfilController@changePassword')->only('auth');
$router->post('/perfil/actualizar-avatar', 'Controles/Api/PerfilController@updateAvatar')->only('auth');
$router->post('/perfil/desactivar', 'Controles/Api/PerfilController@deactivate')->only('auth');
$router->get('/configuracion', 'Controles/Api/PerfilController@index')->only('auth');

// ========== SECCIÓN DE ADMINISTRACIÓN ==========
$router->get('/admin/dashboard', 'Controles/Api/AdminController@dashboard')->only('admin');
$router->get('/admin/reportes', 'Controles/Api/AdminController@reportes')->only('admin');

// Admin - Usuarios
$router->get('/admin/usuarios', 'Controles/Api/AdminController@users')->only('admin');
$router->post('/admin/usuario/{id}/baja', 'Controles/Api/AdminController@deactivateUser')->only('admin');
$router->post('/admin/usuario/{id}/activar', 'Controles/Api/AdminController@activateUser')->only('admin');

// Admin - Publicaciones
$router->get('/admin/publicaciones', 'Controles/Api/AdminController@posts')->only('admin');
$router->post('/admin/publicaciones/{id}/ocultar', 'Controles/Api/AdminController@hidePost')->only('admin');
$router->post('/admin/publicaciones/{id}/mostrar', 'Controles/Api/AdminController@showPost')->only('admin');


// ========== VISTAS PÚBLICAS ==========
$router->get('/', 'controls/inicio.php');
$router->get('/equipos', 'controls/equipos.php');
$router->get('/campeonatos', 'controls/campeonatos.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');
$router->get('/publicaciones', 'controls/Post.php');