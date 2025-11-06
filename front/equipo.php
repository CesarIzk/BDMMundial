<?php require 'partials/header.php'; ?>

<section class="hero hero-equipos">
  <div class="hero-contenido">
    <h2>Equipos del Mundial</h2>
    <p>Selecciona un país para ver su historia, logros y material multimedia.</p>
  </div>
</section>

<section class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Explora las Selecciones Nacionales</h2>

    <?php if (!empty($paises)): ?>
      <!-- 🔍 Selector de país -->
      <div class="selector-pais mb-4">
        <label for="paisSelect">Selecciona un país:</label>
        <select id="paisSelect" onchange="redirigirPais()" class="form-select">
          <option value="">-- Elegir --</option>
          <?php foreach ($paises as $p): ?>
            <option value="<?= htmlspecialchars($p['codigo']) ?>">
              <?= htmlspecialchars($p['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- ✅ Galería de banderas clicables -->
      <div class="paises-grid">
        <?php foreach ($paises as $p): ?>
          <div class="pais-card" onclick="redirigirPais('<?= htmlspecialchars($p['codigo']) ?>')">
            <img 
              src="<?= htmlspecialchars($p['bandera'] ?? 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg') ?>" 
              alt="Bandera de <?= htmlspecialchars($p['nombre']) ?>">
            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-muted py-5">
        ⚠️ No hay países registrados actualmente.  
        El administrador puede agregarlos desde el panel de administración.
      </p>
    <?php endif; ?>
  </div>
</section>

<script>
function redirigirPais(pais) {
  if (!pais) pais = document.getElementById('paisSelect').value;
  if (pais) window.location.href = `/equipos/${pais}`;
}
</script>

<?php require 'partials/footer.php'; ?>
