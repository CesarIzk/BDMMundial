<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  
  <title>Panel de Admin - MundialFan</title>

  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/css/styles.css?v=1.0.16">
</head>

<body>
  <header class="admin-header">
    <div class="header-contenido contenedor d-flex align-items-center justify-content-between">
      <a href="/admin/dashboard" class="logo d-flex align-items-center gap-2 text-decoration-none text-white">
        <i class="fas fa-shield-halved fa-lg"></i>
        <h1 class="m-0 fs-4">MundialFan - Panel Admin</h1>
      </a>
    </div>
  </header>

  <nav class="admin-nav">
    <div class="nav-wrap d-flex align-items-center justify-content-between">
      <!-- Botón hamburguesa -->
      <button class="menu-toggle admin-toggle btn btn-outline-light" aria-label="Menú">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Menú principal -->
      <ul class="navbar d-flex flex-wrap align-items-center gap-3" id="admin-navbar-menu">
        <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li><a href="/admin/usuarios"><i class="fas fa-users-cog"></i> <span>Usuarios</span></a></li>
        <li><a href="/admin/publicaciones"><i class="fas fa-file-alt"></i> <span>Publicaciones</span></a></li>
        <li><a href="/admin/reportes"><i class="fas fa-chart-line"></i> <span>Reportes</span></a></li>
        <li><a href="/admin/crear"><i class="fas fa-user-shield"></i> <span>Crear Admin</span></a></li>
        <li><a href="/"><i class="fas fa-globe"></i> <span>Ver Sitio</span></a></li>
      </ul>

      <!-- Botones de usuario y modo -->
      <div class="auth-buttons d-flex align-items-center gap-3">
        <!-- Modo oscuro -->
        <button id="toggle-mode-header" class="toggle-btn btn btn-outline-light" aria-label="Cambiar modo">
          <i class="fas fa-moon"></i>
        </button>

        <!-- Perfil -->
        <?php if ($isLoggedIn && $user): ?>
          <div class="dropdown">
            <button 
              class="btn btn-light btn-sm d-flex align-items-center gap-2"
              id="adminDropdown" 
              data-bs-toggle="dropdown" 
              aria-expanded="false"
            >
              <div class="user-avatar rounded-circle text-white d-flex align-items-center justify-content-center" 
                   style="width:32px; height:32px; background-color:#c0392b;">
                <?= strtoupper(substr($user['username'] ?? 'A', 0, 1)); ?>
              </div>
              <span class="d-none d-md-inline text-dark"><?= htmlspecialchars($user['username'] ?? 'Admin'); ?></span>
              <i class="fas fa-chevron-down small text-muted"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="adminDropdown">
              <li><a class="dropdown-item" href="/perfil"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>
