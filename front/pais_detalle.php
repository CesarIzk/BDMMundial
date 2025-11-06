<?php require 'partials/header.php'; ?>

<?php if (empty($pais) || (empty($pais['historia']) && empty($imagenes) && empty($videos))): ?>
  <!-- =========================
       FACHADA: País sin información
       ========================= -->
  <section class="hero hero-pais" style="background: linear-gradient(135deg, #333, #111);">
    <div class="overlay"></div>
    <div class="hero-contenido text-center">
      <h2><?= htmlspecialchars($pais['nombre'] ?? 'País en construcción') ?></h2>
      <p class="text-muted">🚧 Este país aún no tiene información registrada. Muy pronto podrás conocer su historia, estadísticas y más.</p>
    </div>
  </section>

  <section class="caracteristicas">
    <div class="contenedor text-center py-5">
      <img 
        src="<?= htmlspecialchars($pais['bandera'] ?? 'https://upload.wikimedia.org/wikipedia/commons/6/65/No-Image-Placeholder.svg') ?>" 
        alt="Bandera no disponible" 
        class="rounded shadow-sm mb-4" 
        style="width:120px; height:auto;"
      >
      <p class="text-muted mb-4">Aún no hay datos sobre este país, pero el equipo de administración está trabajando en ello.</p>
      <a href="/equipos" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Volver a Equipos
      </a>
    </div>
  </section>

<?php else: ?>
  <!-- =========================
       CONTENIDO COMPLETO DEL PAÍS
       ========================= -->
  <section class="hero hero-pais" style="background-image: url('<?= htmlspecialchars($pais['bandera']) ?>');">
    <div class="overlay"></div>
    <div class="hero-contenido">
      <h2><?= htmlspecialchars($pais['nombre']) ?></h2>
      <p><?= htmlspecialchars($pais['descripcion']) ?></p>
    </div>
  </section>

  <section class="caracteristicas">
    <div class="contenedor">
      <h2 class="titulo-seccion">Historia y Datos</h2>
      <p><?= nl2br(htmlspecialchars($pais['historia'])) ?></p>

      <div class="datos-pais">
        <div><strong>Títulos Mundiales:</strong> <?= $pais['titulos'] ?? 0 ?></div>
        <div><strong>Participaciones:</strong> <?= $pais['participaciones'] ?? 0 ?></div>
        <div><strong>Continente:</strong> <?= htmlspecialchars($pais['continente'] ?? 'N/D') ?></div>
        <div><strong>Entrenador:</strong> <?= htmlspecialchars($pais['entrenador'] ?? 'No registrado') ?></div>
        <div><strong>Mejor Jugador:</strong> <?= htmlspecialchars($pais['mejorJugador'] ?? 'No definido') ?></div>
      </div>

      <?php if (!empty($imagenes)): ?>
        <h3 class="subtitulo mt-5">Galería</h3>
        <div class="galeria">
          <?php foreach ($imagenes as $img): ?>
            <img 
              src="<?= htmlspecialchars($img['url']) ?>" 
              alt="<?= htmlspecialchars($img['descripcion']) ?>" 
              class="rounded shadow-sm"
            >
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($videos)): ?>
        <h3 class="subtitulo mt-5">Videos</h3>
        <div class="videos">
          <?php foreach ($videos as $vid): ?>
            <iframe 
              src="<?= htmlspecialchars($vid['url']) ?>" 
              frameborder="0" 
              allowfullscreen
              class="shadow-sm"
            ></iframe>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<?php require 'partials/footer.php'; ?>
