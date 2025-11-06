<?php require 'partials/header.php'; ?>

<div class="container mt-4">
  <h2><i class="fas fa-edit"></i> Editar País: <?= htmlspecialchars($pais['nombre']) ?></h2>

  <form method="POST" action="/admin/paises/<?= $pais['idPais'] ?>/actualizar">
    <div class="row mt-3">
      <div class="col-md-6">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($pais['nombre']) ?>">
      </div>
      <div class="col-md-6">
        <label>Continente</label>
        <input type="text" name="continente" class="form-control" value="<?= htmlspecialchars($pais['continente']) ?>">
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-4">
        <label>Títulos</label>
        <input type="number" name="titulos" class="form-control" value="<?= $pais['titulos'] ?>">
      </div>
      <div class="col-md-4">
        <label>Participaciones</label>
        <input type="number" name="participaciones" class="form-control" value="<?= $pais['participaciones'] ?>">
      </div>
      <div class="col-md-4">
        <label>Entrenador</label>
        <input type="text" name="entrenador" class="form-control" value="<?= htmlspecialchars($pais['entrenador']) ?>">
      </div>
    </div>

    <div class="mt-3">
      <label>Mejor Jugador</label>
      <input type="text" name="mejorJugador" class="form-control" value="<?= htmlspecialchars($pais['mejorJugador']) ?>">
    </div>

    <div class="mt-3">
      <label>Bandera (URL)</label>
      <input type="text" name="bandera" class="form-control" value="<?= htmlspecialchars($pais['bandera']) ?>">
    </div>

    <div class="mt-3">
      <label>Descripción</label>
      <textarea name="descripcion" rows="3" class="form-control"><?= htmlspecialchars($pais['descripcion']) ?></textarea>
    </div>

    <div class="mt-3">
      <label>Historia</label>
      <textarea name="historia" rows="5" class="form-control"><?= htmlspecialchars($pais['historia']) ?></textarea>
    </div>

    <button class="btn btn-success mt-4">
      <i class="fas fa-save"></i> Guardar cambios
    </button>
  </form>
</div>

<?php require 'partials/footer.php'; ?>
