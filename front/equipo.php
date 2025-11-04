<?php require 'partials/header.php'; ?>

<!-- =========================
     HERO - Equipos del Mundial
     ========================= -->
<section class="hero hero-equipos">
  <div class="hero-contenido">
    <h2>Equipos del Mundial</h2>
    <p>Selecciona un país para ver su historia, logros y material multimedia.</p>
  </div>
</section>

<!-- =========================
     SECCIÓN DE EQUIPOS
     ========================= -->
<section class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Explora las Selecciones Nacionales</h2>

    <!-- 🔍 Selector de país -->
    <div class="selector-pais">
      <label for="paisSelect">Selecciona un país:</label>
      <select id="paisSelect" onchange="redirigirPais()">
        <option value="">-- Elegir --</option>
        <option value="argentina">🇦🇷 Argentina</option>
        <option value="brasil">🇧🇷 Brasil</option>
        <option value="españa">🇪🇸 España</option>
        <option value="francia">🇫🇷 Francia</option>
        <option value="alemania">🇩🇪 Alemania</option>
        <option value="mexico">🇲🇽 México</option>
      </select>
    </div>

    <!-- ✅ Galería de banderas clicables -->
    <div class="paises-grid">
      <div class="pais-card" onclick="redirigirPais('argentina')">
        <img src="https://flagcdn.com/w320/ar.png" alt="Bandera de Argentina">
        <h3>Argentina</h3>
      </div>
      <div class="pais-card" onclick="redirigirPais('brasil')">
        <img src="https://flagcdn.com/w320/br.png" alt="Bandera de Brasil">
        <h3>Brasil</h3>
      </div>
      <div class="pais-card" onclick="redirigirPais('españa')">
        <img src="https://flagcdn.com/w320/es.png" alt="Bandera de España">
        <h3>España</h3>
      </div>
      <div class="pais-card" onclick="redirigirPais('francia')">
        <img src="https://flagcdn.com/w320/fr.png" alt="Bandera de Francia">
        <h3>Francia</h3>
      </div>
      <div class="pais-card" onclick="redirigirPais('alemania')">
        <img src="https://flagcdn.com/w320/de.png" alt="Bandera de Alemania">
        <h3>Alemania</h3>
      </div>
      <div class="pais-card" onclick="redirigirPais('mexico')">
        <img src="https://flagcdn.com/w320/mx.png" alt="Bandera de México">
        <h3>México</h3>
      </div>
    </div>
  </div>
</section>

<script>
// =============================
// FUNCIÓN DE REDIRECCIÓN
// =============================
function redirigirPais(pais) {
  if (!pais) pais = document.getElementById('paisSelect').value;
  if (pais) {
    window.location.href = `/equipos/${pais}`;
  }
}
</script>

<?php require 'partials/footer.php'; ?>
