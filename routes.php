<?php

// ========== AUTENTICACIÓN ==========
$router->post('/login', 'Controles/Api/AuthController@login');
$router->post('/register', 'Controles/Api/AuthController@register');
$router->get('/logout', 'Controles/Api/AuthController@logout');

// ========== PUBLICACIONES ==========
$router->get('/Post', 'Controles\Api\PostController@index');
$router->post('/Post/store', 'Controles\Api\PostController@store');
$router->post('/Post/like', 'Controles\Api\PostController@like');
$router->get('/Post/create', 'Controles\Api\PostController@create');

// En tu archivo de rutas, ANTES de la ruta con {id}
$router->get('/Post/view', 'Controles\Api\PostController@show'); // Nueva ruta
$router->get('/Post/{id}', 'Controles\Api\PostController@show'); // Mantener esta
// ========== PERFIL ==========
$router->get('/perfil', 'controls/perfil.php')->only('auth');
$router->get('/perfil/show', 'Controles/Api/PerfilController@show')->only('auth');
$router->get('/configuracion', 'Controles/Api/PerfilController@index')->only('auth');

// --- Acciones del perfil ---
$router->post('/perfil/update', 'Controles/Api/PerfilController@update')->only('auth');
$router->post('/perfil/avatar', 'Controles/Api/PerfilController@updateAvatar')->only('auth');
$router->post('/perfil/password', 'Controles/Api/PerfilController@changePassword')->only('auth');
$router->post('/perfil/deactivate', 'Controles/Api/PerfilController@deactivate')->only('auth');

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

// ==================== ADMIN - PAÍSES ====================

// Vista general de países
$router->get('/admin/paises', 'Controles/Api/AdminPaisController@index')->only('admin');
// Formulario de edición
$router->get('/admin/paises/{id}/editar', 'Controles/Api/AdminPaisController@edit')->only('admin');
// Guardar cambios
$router->post('/admin/paises/{id}/actualizar', 'Controles/Api/AdminPaisController@update')->only('admin');
// Crear nuevo país
$router->get('/admin/paises/crear', 'Controles/Api/AdminPaisController@create')->only('admin');
$router->post('/admin/paises/store', 'Controles/Api/AdminPaisController@store')->only('admin');

// ========== VISTAS PÚBLICAS ==========
$router->get('/', 'controls/inicio.php');
$router->get('/tienda', 'controls/tienda.php');
$router->get('/stats', 'controls/stats.php');
$router->get('/publicaciones', 'controls/Post.php');

// ========== CAMPEONATOS ==========
$router->get('/campeonatos', 'Controles/Api/CampeonatoController@index');
$router->get('/campeonatos/{anio}', 'Controles/Api/CampeonatoController@show');
// ✅ Esta es la que debe quedar
$router->get('/campeonatos', 'Controles/Api/CampeonatoController@index');
$router->get('/campeonatos/{anio}', 'Controles/Api/CampeonatoController@show');

// ========== EQUIPOS / PAISES ==========
$router->get('/equipos', 'Controles/Api/PaisController@index');
$router->get('/equipos/{pais}', 'Controles/Api/PaisController@show');


// === COMENTARIOS ===
$router->get('/api/comentarios/{id}', 'Controles\Api\ComentarioController@index'); // obtener comentarios de un post
$router->post('/api/comentarios', 'Controles\Api\ComentarioController@store');     // crear comentario
$router->delete('/api/comentarios/{id}', 'Controles\Api\ComentarioController@delete'); // eliminar comentario

