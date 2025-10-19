<?php
// front/partials/header.php
// Detecta si hay sesión activa
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MundialFan - Todo sobre el Mundial de Fútbol</title>

  <!-- Fuente principal -->
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">

  <!-- Bootstrap y Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSS fragmentado -->
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
  <!-- Header -->
  <header>
    <div class="header-contenido contenedor">
      <a href="/" class="logo">
        <i class="fas fa-futbol"></i>
        <h1>MundialFan</h1>
      </a>
    </div>
  </header>

  <!-- Navbar -->
  <nav>
    <div class="nav-wrap contenedor">
      <button class="menu-toggle" id="menu-toggle" aria-label="Menú">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="navbar" id="navbar-menu">
        <li><a href="/"><i class="fas fa-home"></i> <span>Inicio</span></a></li>
        <li><a href="/campeonatos"><i class="fas fa-trophy"></i> <span>Campeonatos</span></a></li>
        <li><a href="/equipos"><i class="fas fa-users"></i> <span>Equipos</span></a></li>
        <li><a href="/Post"><i class="fas fa-calendar-alt"></i> <span>Publicaciones</span></a></li>
        <li><a href="/tienda"><i class="fas fa-store"></i> <span>Tienda</span></a></li>
        <li><a href="/contacto"><i class="fas fa-envelope"></i> <span>Contacto</span></a></li>
      </ul>

      <div class="auth-buttons">
        <!-- Si hay sesión: mostrar perfil del usuario -->
        <?php if ($isLoggedIn): ?>
          <div class="dropdown">
            <button class="btn btn-sm user-profile" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Perfil">
              <div class="user-avatar">
                <?php echo strtoupper(substr($user['Nombre'] ?? 'U', 0, 1)); ?>
              </div>
              <span><?php echo htmlspecialchars($user['Nombre'] ?? 'Usuario'); ?></span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/perfil"><i class="fas fa-user"></i> Mi Perfil</a></li>
              <li><a class="dropdown-item" href="/configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
          </div>
        <!-- Si NO hay sesión: mostrar botón de login -->
        <?php else: ?>
          <button class="btn-login" data-bs-toggle="modal" data-bs-target="#authModal" aria-label="Ingresar">
            <i class="fas fa-sign-in-alt"></i> <span>Ingresar</span>
          </button>
        <?php endif; ?>

        <!-- Botón tema oscuro -->
        <button class="toggle-btn" id="toggle-mode" aria-label="Cambiar modo">
          <i class="fas fa-moon"></i>
        </button>
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
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" aria-controls="login-panel" aria-selected="true">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab" aria-controls="register-panel" aria-selected="false">
                <i class="fas fa-user-plus"></i> Registrarse
              </button>
            </li>
          </ul>

          <div class="tab-content mt-4">
            <!-- Panel de Login -->
            <div class="tab-pane fade show active" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
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

            <!-- Panel de Registro -->
            <div class="tab-pane fade" id="register-panel" role="tabpanel" aria-labelledby="register-tab">
              <form id="register-form" action="/register" method="POST" enctype="multipart/form-data">
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

  <!-- Modal de Mensajes -->
  <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="mensajeModalBody"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle del menú móvil
    const menuToggle = document.getElementById('menu-toggle');
    const navbarMenu = document.getElementById('navbar-menu');

    if (menuToggle) {
      menuToggle.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
      });
    }

    // Cerrar menú al hacer click en un link
    document.querySelectorAll('.navbar a').forEach(link => {
      link.addEventListener('click', () => {
        navbarMenu.classList.remove('active');
      });
    });

  </script>

  <script src="/js/script.js"></script>
</body>
</html>