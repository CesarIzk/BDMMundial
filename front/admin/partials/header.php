<?php
// front/admin/partials/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asumimos que el router ya validó que es un admin
// pero igual tomamos sus datos de sesión
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
  
  <link rel="stylesheet" href="/css/styles.css?v=1.0.13">
  <link rel="stylesheet" href="/css/admin.css?v=1.0.1"> </head>

<body>
  <header>
    <div class="header-contenido contenedor">
      <a href="/admin/dashboard" class="logo"> <i class="fas fa-shield-halved"></i>
        <h1>MundialFan - Panel de Admin</h1>
      </a>
    </div>
  </header>

  <nav>
    <div class="nav-wrap">
      <button class="menu-toggle" aria-label="Menú">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="navbar" id="navbar-menu">
        <li><a href="/admin/dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li><a href="/admin/usuarios"><i class="fas fa-users-cog"></i> <span>Usuarios</span></a></li>
        <li><a href="/admin/publicaciones"><i class="fas fa-file-alt"></i> <span>Publicaciones</span></a></li>
        <li><a href="/admin/reportes"><i class="fas fa-chart-line"></i> <span>Reportes</span></a></li>
        <li><a href="/"><i class="fas fa-globe"></i> <span>Ver Sitio</span></a></li>
      </ul>

      <div class="auth-buttons">
        
        <button id="toggle-mode-header" class="toggle-btn" aria-label="Cambiar modo">
          <i class="fas fa-moon"></i>
        </button>

        <?php if ($isLoggedIn && $user): ?>
          <div class="dropdown">
            <button class="btn btn-sm user-profile" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="user-avatar" style="background-color: #c0392b;"> <?php echo strtoupper(substr($user['username'] ?? 'A', 0, 1)); ?>
              </div>
              <span class="d-none d-md-inline"><?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?></span>
              <i class="fas fa-chevron-down ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="/perfil"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php endif; ?>
        </div>
    </div>
  </nav>

  <main class="admin-container container mt-4">