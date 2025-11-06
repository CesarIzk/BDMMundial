<?php require 'partials/header.php'; ?>

<div class="container mt-4">
  <h2><i class="fas fa-plus-circle"></i> Agregar Nuevo País</h2>
  <p class="text-muted">Completa los campos para registrar un nuevo país en la base de datos.</p>

  <form method="POST" action="/admin/paises/store">
    <div class="row mt-3">
      <div class="col-md-4">
        <label>Código (slug)</label>
        <input type="text" name="codigo" class="form-control" placeholder="ej. argentina, brasil" required>
      </div>
      <div class="col-md-4">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" placeholder="Nombre del país" required>
      </div>
      <div class="col-md-4">
        <label>Continente</label>
        <input type="text" name="continente" class="form-control" placeholder="Ej. América del Sur">
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-4">
        <label>Títulos</label>
        <input type="number" name="titulos" class="form-control" value="0">
      </div>
      <div class="col-md-4">
        <label>Participaciones</label>
        <input type="number" name="participaciones" class="form-control" value="0">
      </div>
      <div class="col-md-4">
        <label>Entrenador</label>
        <input type="text" name="entrenador" class="form-control" placeholder="Nombre del entrenador actual">
      </div>
    </div>

    <div class="mt-3">
      <label>Mejor Jugador</label>
      <input type="text" name="mejorJugador" class="form-control" placeholder="Ej. Lionel Messi">
    </div>

    <div class="mt-3">
      <label>Bandera (URL)</label>
      <input type="text" name="bandera" class="form-control" placeholder="https://flagcdn.com/w320/ar.png">
    </div>

    <div class="mt-3">
      <label>Descripción</label>
      <textarea name="descripcion" rows="3" class="form-control" placeholder="Breve descripción del país o su selección."></textarea>
    </div>

    <div class="mt-3">
      <label>Historia</label>
      <textarea name="historia" rows="5" class="form-control" placeholder="Historia deportiva del país."></textarea>
    </div>

    <button class="btn btn-success mt-4">
      <i class="fas fa-save"></i> Guardar País
    </button>
  </form>
</div>

<?php require 'partials/footer.php'; ?>
