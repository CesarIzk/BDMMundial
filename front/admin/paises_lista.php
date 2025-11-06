<?php require 'partials/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
  <h2><i class="fas fa-flag"></i> Administración de Países</h2>
  <a href="/admin/paises/crear" class="btn btn-primary">
    <i class="fas fa-plus"></i> Nuevo País
  </a>
</div>

  <table class="table table-striped table-hover mt-3">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Continente</th>
        <th>Títulos</th>
        <th>Participaciones</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($paises as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['continente'] ?? '-') ?></td>
          <td><?= $p['titulos'] ?></td>
          <td><?= $p['participaciones'] ?></td>
          <td>
            <a href="/admin/paises/<?= $p['idPais'] ?>/editar" class="btn btn-sm btn-warning">
              <i class="fas fa-edit"></i> Editar
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require 'partials/footer.php'; ?>
