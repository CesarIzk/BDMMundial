<?php require 'partials/header.php'; ?>

<?php if (!empty($userData)): ?>
<section class="perfil py-5 bg-light">
  <div class="container">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
      <div class="card-header bg-primary text-white d-flex align-items-center">
        <i class="bi bi-person-circle me-2 fs-3"></i>
        <h2 class="mb-0">Mi Perfil</h2>
      </div>

      <div class="card-body p-4">
        <div class="row align-items-center">
          <!-- Avatar -->
          <div class="col-md-4 d-flex justify-content-center align-items-center mb-4 mb-md-0">
            <?php if (!empty($userData['fotoPerfil'])): ?>
              <img src="<?php echo htmlspecialchars($userData['fotoPerfil']); ?>" 
                   alt="Avatar" 
                   class="img-fluid rounded-circle shadow-sm border border-3 border-primary"
                   style="width: 180px; height: 180px; object-fit: cover;">
            <?php else: ?>
              <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                   style="width: 180px; height: 180px; font-size: 4rem;">
                <?php echo strtoupper(substr($userData['Nombre'], 0, 1)); ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Información -->
          <div class="col-md-8">
            <h3 class="fw-bold">
              <?php echo htmlspecialchars($userData['Nombre']); ?>
              <small class="text-muted">@<?php echo htmlspecialchars($userData['username']); ?></small>
            </h3>

            <ul class="list-group list-group-flush mt-3">
              <li class="list-group-item">
                <i class="bi bi-envelope-fill text-primary me-2"></i>
                <strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?>
              </li>

              <?php if (!empty($userData['biografia'])): ?>
              <li class="list-group-item">
                <i class="bi bi-book text-primary me-2"></i>
                <strong>Biografía:</strong><br>
                <span class="text-muted"><?php echo nl2br(htmlspecialchars($userData['biografia'])); ?></span>
              </li>
              <?php endif; ?>

              <?php if (!empty($userData['fechaNacimiento'])): ?>
              <li class="list-group-item">
                <i class="bi bi-calendar-date text-primary me-2"></i>
                <strong>Fecha de Nacimiento:</strong> 
                <?php echo date('d/m/Y', strtotime($userData['fechaNacimiento'])); ?>
              </li>
              <?php endif; ?>

              <?php if (!empty($userData['genero'])): ?>
              <li class="list-group-item">
                <i class="bi bi-gender-ambiguous text-primary me-2"></i>
                <strong>Género:</strong> <?php echo htmlspecialchars($userData['genero']); ?>
              </li>
              <?php endif; ?>

              <?php if (!empty($userData['ciudad']) || !empty($userData['pais'])): ?>
              <li class="list-group-item">
                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                <strong>Ubicación:</strong> 
                <?php echo htmlspecialchars($userData['ciudad'] ?? '-') . ', ' . htmlspecialchars($userData['pais'] ?? '-'); ?>
              </li>
              <?php endif; ?>
            </ul>

            <div class="mt-4">
              <a href="/configuracion" class="btn btn-primary px-4">
                <i class="bi bi-pencil-square me-1"></i> Editar Perfil
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
<p class="text-center mt-5 text-danger">Usuario no encontrado.</p>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>
