<?php
// front/partials/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MundialFan - Todo sobre el Mundial de Fútbol</title>

  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/css/styles.css?v=1.0.13">
</head>

<body>
  <header>
    <div class="header-contenido contenedor">
      <a href="/" class="logo">
        <i class="fas fa-futbol"></i>
        <h1>MundialFan - BETAv</h1>
      </a>
    </div>
  </header>

  <nav>
    <div class="nav-wrap">
      <button class="menu-toggle" aria-label="Menú">
        <i class="fas fa-bars"></i>
      </button>

     <ul class="navbar" id="navbar-menu">
        <li><a href="/"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="/campeonatos"><i class="fas fa-trophy"></i> <span>Campeonatos</span></a></li>
        <li><a href="/equipos"><i class="fas fa-users"></i> <span>Equipos</span></a></li>
        <li><a href="/public"><i class="fas fa-calendar-alt"></i> <span>Publicaciones</span></a></li>
        <li><a href="/stats"><i class="fas fa-chart-bar"></i> <span>Estadísticas</span></a></li>
        <li><a href="/tienda"><i class="fas fa-store"></i> <span>Tienda</span></a></li>
  
      </ul>
    

      <div class="auth-buttons">
        <?php if ($isLoggedIn): ?>
          <a href="/public/create" class="btn-crear-post" title="Nueva Publicación">
            <i class="fas fa-plus-circle"></i> <span>Publicar</span>
          </a>
        <?php endif; ?>

        <button id="toggle-mode-header" class="toggle-btn" aria-label="Cambiar modo">
          <i class="fas fa-moon"></i>
        </button>

    <?php if ($isLoggedIn): ?>
  <div class="dropdown">
    <button class="btn btn-sm user-profile d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

      <!-- 🧑 AVATAR o INICIAL -->
      <?php if (!empty($user['fotoPerfil'])): ?>
        <img src="<?= htmlspecialchars($user['fotoPerfil']) ?>"
             alt="Avatar de <?= htmlspecialchars($user['Nombre'] ?? 'Usuario') ?>"
             class="rounded-circle shadow-sm border border-primary"
             style="width: 36px; height: 36px; object-fit: cover;">
      <?php else: ?>
        <div class="user-avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
             style="width: 36px; height: 36px; font-size: 1rem;">
          <?= strtoupper(substr($user['Nombre'] ?? 'U', 0, 1)) ?>
        </div>
      <?php endif; ?>

      <!-- 🧾 NOMBRE + ICONO -->
<span class="d-none d-md-inline fw-semibold">@<?= htmlspecialchars($user['username'] ?? 'Usuario') ?></span>
      <i class="fas fa-chevron-down ms-1 text-muted"></i>
    </button>

    <!-- 🔽 MENÚ DESPLEGABLE -->
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
      <li><a class="dropdown-item" href="/perfil"><i class="fas fa-user me-2 text-primary"></i> Mi Perfil</a></li>
      <li><a class="dropdown-item" href="/configuracion"><i class="fas fa-cog me-2 text-secondary"></i> Configuración</a></li>
      <?php if ($isAdmin): ?>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="/admin/dashboard"><i class="fas fa-shield-alt me-2 text-info"></i> Panel Admin</a></li>
      <?php endif; ?>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
    </ul>
  </div>
<?php else: ?>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#authModal">
    <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-md-inline">Ingresar</span>
  </button>
<?php endif; ?>



      </div>
    </div>
  </nav>

  <!-- Modal de Autenticación -->
  <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="authModalLabel">
            <i class="fas fa-lock"></i> Autenticación
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        
        <div class="modal-body">
          <!-- Mensajes de Error/Éxito -->
          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
          <?php endif; ?>

          <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
          <?php endif; ?>

          <!-- Tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab">
                <i class="fas fa-user-plus"></i> Registrarse
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content mt-4">
            <!-- Login Panel -->
            <div class="tab-pane fade show active" id="login-panel" role="tabpanel">
              <form id="login-form" action="/login" method="POST">
                <div class="mb-3">
                  <label for="correo-login" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="correo-login" name="correo" placeholder="tu@email.com" required>
                </div>
                <div class="mb-3">
                  <label for="contrasena-login" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="contrasena-login" name="contrasena" placeholder="••••••••" required>
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                  <label class="form-check-label" for="rememberMe">Recuérdame</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
              </form>
            </div>

            <!-- Register Panel -->
            <div class="tab-pane fade" id="register-panel" role="tabpanel">
              <form id="register-form" action="/register" method="POST">
                <div class="mb-3">
                  <label for="nombreCom" class="form-label">Nombre completo</label>
                  <input type="text" class="form-control" id="nombreCom" name="nombreCom" placeholder="Tu nombre" required>
                </div>
                <div class="mb-3">
                  <label for="correo-reg" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="correo-reg" name="correo" placeholder="tu@email.com" required>
                </div>
                <div class="mb-3">
                  <label for="contrasena-reg" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="contrasena-reg" name="contrasena" placeholder="••••••••" required>
                </div>
                <div class="mb-3">
                  <label for="contrasena2" class="form-label">Confirmar contraseña</label>
                  <input type="password" class="form-control" id="contrasena2" name="contrasena2" placeholder="••••••••" required>
                </div>
                <div class="mb-3">
                  <label for="nacionalidad" class="form-label">País</label>
                  <select class="form-select" name="nacionalidad" id="nacionalidad" required>
                    <option value="">Selecciona tu país</option>
                    <option value="Mexico">México</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Argentina">Argentina</option>
                    <option value="España">España</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Crear Cuenta</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/script.js"></script>
  <script src="/js/dark-mode.js"></script>
  <script src="/js/navbar-mobile.js"></script>
  
  <script>
  // Abrir modal automáticamente si hay error/success
  document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
      var authModal = new bootstrap.Modal(document.getElementById('authModal'));
      authModal.show();
    <?php endif; ?>

    // Validación del formulario de registro
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
      registerForm.addEventListener('submit', function(e) {
        const pass1 = document.getElementById('contrasena-reg').value;
        const pass2 = document.getElementById('contrasena2').value;
        
        if (pass1 !== pass2) {
          e.preventDefault();
          alert('Las contraseñas no coinciden');
          return false;
        }
        
        if (pass1.length < 4) {
          e.preventDefault();
          alert('La contraseña debe tener al menos 4 caracteres');
          return false;
        }
      });
    }
  });
  </script>
  <script>
document.addEventListener('DOMContentLoaded', function() {
  const userProfile = document.querySelector('.user-profile');
  const dropdownMenu = document.querySelector('.dropdown-menu');
  
  if (userProfile && dropdownMenu) {
    userProfile.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });
    
    // Cerrar al hacer click fuera
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        dropdownMenu.classList.remove('show');
      }
    });
  }
});
</script>
</body>
</html>