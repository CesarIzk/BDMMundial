<?php require 'partials/header.php'; ?>

<section class="hero hero-campeonatos">
  <div class="hero-slideshow">
    <div class="hero-slide"></div>
    <div class="hero-slide"></div>
    <div class="hero-slide"></div>
  </div>
  <div class="hero-contenido">
    <h2>Vive la Emoción del Mundial</h2>
    <p>Todo lo que necesitas saber sobre el mayor evento de fútbol del planeta. Noticias, estadísticas, resultados y mucho más.</p>
    <div class="hero-buttons">
      <a href="#" class="boton">Ver Partidos</a>
      <a href="#" class="boton">Comprar Entradas</a>
    </div>
  </div>
</section>

<!-- HISTORIA -->
<section id="info" class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Historia del Mundial de Fútbol</h2>
    <p>
      El Mundial de Fútbol es el torneo internacional más importante del fútbol, organizado cada cuatro años por la FIFA. 
      Desde 1930, millones de aficionados siguen a sus selecciones nacionales en busca de la gloria.
    </p>
  </div>
</section>

<!-- EDICIONES -->
<section id="ediciones" class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Ediciones del Mundial</h2>

    <!-- Filtro de búsqueda -->
    <div class="filtro-ediciones">
      <input 
        type="text" 
        id="filtroInput" 
        placeholder="Buscar por año, sede o campeón..." 
        class="input-filtro"
        onkeyup="filtrarEdiciones()"
      >
    </div>

    <?php if (!empty($ediciones) && count($ediciones) > 0): ?>
      <ul class="ediciones-lista">
        <?php foreach ($ediciones as $c): ?>
          <li class="edicion-item">
            <img src="<?= htmlspecialchars($c['bandera']) ?>" class="flag" alt="<?= htmlspecialchars($c['paisSede']) ?>">

            <div class="edicion-info">
              <h3>
                <?= htmlspecialchars($c['anio']) ?> - <?= htmlspecialchars($c['paisSede']) ?>
              </h3>
              <p><?= htmlspecialchars($c['descripcion']) ?></p>

              <div class="edicion-resultados">
                <span class="campeon">🏆 <?= htmlspecialchars($c['campeon']) ?></span>
                <span class="subcampeon">🥈 <?= htmlspecialchars($c['subcampeon']) ?></span>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No hay ediciones registradas en la base de datos.</p>
    <?php endif; ?>
  </div>
</section>


<!-- EQUIPOS EXITOSOS -->
<section id="equipos" class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Equipos más Exitosos</h2>
    <?php if (!empty($equipos) && count($equipos) > 0): ?>
      <ul>
        <?php foreach ($equipos as $e): ?>
          <li>
            <img src="<?= htmlspecialchars($e['bandera']) ?>" class="flag" alt="<?= htmlspecialchars($e['nombre']) ?>">
            <?= htmlspecialchars($e['nombre']) ?> - <?= htmlspecialchars($e['titulos']) ?> títulos
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No hay datos de equipos exitosos registrados.</p>
    <?php endif; ?>
  </div>
</section>

<!-- JUGADORES DESTACADOS -->
<section id="jugadores" class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Jugadores Más Destacados</h2>

    <?php if (!empty($jugadores) && count($jugadores) > 0): ?>
      <div class="jugadores-grid">
        <?php foreach ($jugadores as $j): ?>
          <div class="jugador-card">
            <div class="jugador-foto">
              <img src="<?= htmlspecialchars($j['foto']) ?>" alt="<?= htmlspecialchars($j['nombre']) ?>">
            </div>
            <div class="jugador-info">
              <h3>
                <?= htmlspecialchars($j['nombre']) ?>
                <img src="https://flagicons.lipis.dev/flags/4x3/<?= strtolower(substr($j['pais'], 0, 2)) ?>.svg" 
                     alt="<?= htmlspecialchars($j['pais']) ?>" 
                     class="jugador-bandera">
              </h3>
              <p class="jugador-pais"><?= htmlspecialchars($j['pais']) ?></p>
              <p class="jugador-logros"><?= htmlspecialchars($j['logros']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No hay jugadores destacados registrados aún.</p>
    <?php endif; ?>
  </div>
</section>


<script>
function filtrarEdiciones() {
  const input = document.getElementById("filtroInput");
  const filter = input.value.toLowerCase();
  const items = document.querySelectorAll("#ediciones li");

  items.forEach(item => {
    const text = item.textContent.toLowerCase();
    if (text.includes(filter)) {
      item.style.display = "flex";
      item.style.opacity = "1";
      item.style.transform = "translateY(0)";
    } else {
      item.style.display = "none";
      item.style.opacity = "0";
    }
  });
}
</script>

<?php require 'partials/footer.php'; ?>
