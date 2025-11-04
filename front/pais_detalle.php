<?php require 'partials/header.php'; ?>

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
      <div><strong>Títulos Mundiales:</strong> <?= $pais['titulos'] ?></div>
      <div><strong>Participaciones:</strong> <?= $pais['participaciones'] ?></div>
      <div><strong>Continente:</strong> <?= htmlspecialchars($pais['continente']) ?></div>
      <div><strong>Entrenador:</strong> <?= htmlspecialchars($pais['entrenador']) ?></div>
      <div><strong>Mejor Jugador:</strong> <?= htmlspecialchars($pais['mejorJugador']) ?></div>
    </div>

    <?php if (!empty($imagenes)): ?>
    <h3 class="subtitulo">Galería</h3>
    <div class="galeria">
      <?php foreach ($imagenes as $img): ?>
        <img src="<?= htmlspecialchars($img['url']) ?>" alt="<?= htmlspecialchars($img['descripcion']) ?>">
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($videos)): ?>
    <h3 class="subtitulo">Videos</h3>
    <div class="videos">
      <?php foreach ($videos as $vid): ?>
        <iframe src="<?= htmlspecialchars($vid['url']) ?>" frameborder="0" allowfullscreen></iframe>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require 'partials/footer.php'; ?>
