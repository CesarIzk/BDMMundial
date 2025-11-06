<?php require 'partials/header.php'; ?>

<div class="container py-5">

    <h2 class="mb-4 text-center">
        <i class="fas fa-cog"></i> Configuración de Perfil
    </h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($info)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> <?= htmlspecialchars($info) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ======================== -->
    <!-- 🧍 INFORMACIÓN PERSONAL -->
    <!-- ======================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user"></i> Información Personal</h5>
        </div>
        <div class="card-body">
            <form action="/perfil/update" method="POST" class="form-perfil">

                <div class="mb-3">
                    <label for="Nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre"
                           value="<?= htmlspecialchars($userData['Nombre'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="biografia" class="form-label">Biografía</label>
                    <textarea class="form-control" id="biografia" name="biografia" rows="3"><?= htmlspecialchars($userData['biografia'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                               value="<?= htmlspecialchars($userData['fechaNacimiento'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <select class="form-select" id="genero" name="genero">
                            <option value="">Prefiero no decirlo</option>
                            <option value="Masculino" <?= ($userData['genero'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= ($userData['genero'] ?? '') === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= ($userData['genero'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad"
                               value="<?= htmlspecialchars($userData['ciudad'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pais" class="form-label">País</label>
                        <select class="form-select" id="pais" name="pais">
                            <option value="">Selecciona tu país</option>
                            <option value="México" <?= ($userData['pais'] ?? '') === 'México' ? 'selected' : '' ?>>México</option>
                            <option value="Colombia" <?= ($userData['pais'] ?? '') === 'Colombia' ? 'selected' : '' ?>>Colombia</option>
                            <option value="Argentina" <?= ($userData['pais'] ?? '') === 'Argentina' ? 'selected' : '' ?>>Argentina</option>
                            <option value="España" <?= ($userData['pais'] ?? '') === 'España' ? 'selected' : '' ?>>España</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- ======================== -->
<!-- 🖼️ FOTO DE PERFIL -->
<!-- ======================== -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-secondary text-white">
    <h5 class="mb-0"><i class="fas fa-camera"></i> Actualizar Foto de Perfil</h5>
  </div>
  <div class="card-body">
    
    <div class="perfil-grid">
      <!-- Columna izquierda: Avatar -->
      <div class="perfil-avatar">
        <?php if (!empty($userData['fotoPerfil'])): ?>
          <img src="<?= htmlspecialchars($userData['fotoPerfil']) ?>"
               alt="Foto de perfil actual"
               class="avatar-img rounded-circle shadow">
        <?php else: ?>
          <div class="avatar-placeholder rounded-circle shadow">
            <?= strtoupper(substr($userData['Nombre'] ?? '?', 0, 1)) ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Columna derecha: Formulario -->
      <div class="perfil-info">
        <form action="/perfil/avatar" method="POST" enctype="multipart/form-data" class="mt-3">
          <div class="mb-3">
            <label for="avatar" class="form-label">Selecciona una nueva foto</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
            <small class="text-muted d-block mt-1">
              Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo 5MB.
            </small>
          </div>
          <button type="submit" class="btn btn-secondary">
            <i class="fas fa-upload"></i> Subir nueva foto
          </button>
        </form>
      </div>
    </div>

  </div>
</div>


    <!-- ======================== -->
    <!-- 🔑 CAMBIO DE CONTRASEÑA -->
    <!-- ======================== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-lock"></i> Cambiar Contraseña</h5>
        </div>
        <div class="card-body">
            <form action="/perfil/password" method="POST">
                <div class="mb-3">
                    <label for="actual" class="form-label">Contraseña Actual</label>
                    <input type="password" class="form-control" id="actual" name="actual" required>
                </div>

                <div class="mb-3">
                    <label for="nueva" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="nueva" name="nueva" minlength="4" required>
                </div>

                <div class="mb-3">
                    <label for="confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="confirmar" name="confirmar" minlength="4" required>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-key"></i> Actualizar Contraseña
                </button>
            </form>
        </div>
    </div>

    <!-- ======================== -->
    <!-- ⚠️ ZONA DE PELIGRO -->
    <!-- ======================== -->
    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Zona de Peligro</h5>
        </div>
        <div class="card-body">
            <p>Dar de baja tu cuenta marcará tu perfil como inactivo y cerrará tu sesión inmediatamente. Esta acción no se puede deshacer.</p>
            <form action="/perfil/deactivate" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas dar de baja tu cuenta?');">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-user-slash"></i> Dar de baja mi cuenta
                </button>
            </form>
        </div>
    </div>

</div>

<?php require 'partials/footer.php'; ?>
