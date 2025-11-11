<?php require 'partials/header.php'; ?>

<div class="container mt-5 mb-5">
  <h2 class="mb-4"><i class="fas fa-user-shield"></i> Crear Nuevo Administrador</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-body">
      <form method="POST" action="/admin/crear">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="username" class="form-control" required>
          </div>
      
<div class="row mb-3">
  <div class="col-md-6">
    <label class="form-label">Contraseña del nuevo administrador</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Confirmar contraseña del nuevo administrador</label>
    <input type="password" name="confirm_new_password" class="form-control" required>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label class="form-label">Confirma tu contraseña (admin actual)</label>
    <input type="password" name="confirm_admin_password" class="form-control" required>
    <small class="text-muted">
      Ingresa tu propia contraseña para autorizar la creación del nuevo administrador.
    </small>
  </div>
</div>


        <div class="d-flex justify-content-end">
          <a href="/admin/usuarios" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Cancelar
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Administrador
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require 'partials/footer.php'; ?>
