<?php
// equipos.php
?>
<?php require 'partials/header.php'; ?>
<?php require 'partials/navbar.php'; ?>

<section class="hero" style="background-image: url('/imagenes/equipos.jpg');">
  <div class="hero-contenido">
    <h2>Equipos del Mundial</h2>
    <p>Descubre a las selecciones que compiten por la gloria.</p>
  </div>
</section>

<section class="caracteristicas">
  <div class="contenedor">
    <h2 class="titulo-seccion">Selecciones Nacionales</h2>
    <div class="caracteristicas-grid">
      <?php
      // 🔹 Puedes cambiar esto por datos de BD
      $equipos = [
        ["pais" => "Argentina", "bandera" => "https://flagcdn.com/w320/ar.png"],
        ["pais" => "Brasil", "bandera" => "https://flagcdn.com/w320/br.png"],
        ["pais" => "España", "bandera" => "https://flagcdn.com/w320/es.png"],
        ["pais" => "Francia", "bandera" => "https://flagcdn.com/w320/fr.png"],
        ["pais" => "Alemania", "bandera" => "https://flagcdn.com/w320/de.png"],
        ["pais" => "México", "bandera" => "https://flagcdn.com/w320/mx.png"]
      ];

      foreach ($equipos as $eq) {
        echo "
        <div class='caracteristica'>
          <img src='{$eq['bandera']}' alt='Bandera de {$eq['pais']}' style='width:80px; border-radius:6px; margin-bottom:10px;'>
          <h3>{$eq['pais']}</h3>
          <p>Información, jugadores y estadísticas de la selección de {$eq['pais']}.</p>
          <a href='#' class='boton'>Ver Detalles</a>
        </div>
        ";
      }
      ?>
    </div>
  </div>
</section>

 <?php require 'partials/footer.php'; ?>