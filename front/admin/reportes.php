<?php require 'partials/header.php'; ?>

<div class="container-fluid">
    <h2 class="mb-4">
        <i class="fas fa-chart-line"></i> Reportes y Estadísticas
    </h2>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/reportes" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" 
                           value="<?= $_GET['fecha_inicio'] ?? date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" 
                           value="<?= $_GET['fecha_fin'] ?? date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipo de Reporte</label>
                    <select class="form-select" name="tipo">
                        <option value="general">General</option>
                        <option value="usuarios">Solo Usuarios</option>
                        <option value="publicaciones">Solo Publicaciones</option>
                        <option value="actividad">Actividad</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search"></i> Generar
                    </button>
                    <button type="button" class="btn btn-success" onclick="exportarCSV()">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Métricas Generales -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h3><?= $reportes['totalUsuarios'] ?? 0 ?></h3>
                    <p class="text-muted">Total Usuarios Registrados</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i> 
                        <?= $reportes['crecimientoUsuarios'] ?? 0 ?>% vs mes anterior
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-3x text-success mb-3"></i>
                    <h3><?= $reportes['totalPublicaciones'] ?? 0 ?></h3>
                    <p class="text-muted">Total Publicaciones</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i> 
                        <?= $reportes['crecimientoPublicaciones'] ?? 0 ?>% vs mes anterior
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                    <h3><?= $reportes['totalInteracciones'] ?? 0 ?></h3>
                    <p class="text-muted">Total Interacciones (Likes)</p>
                    <small class="text-info">
                        <i class="fas fa-chart-line"></i> 
                        Promedio: <?= $reportes['promedioLikes'] ?? 0 ?> por post
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Usuarios por País</h5>
                </div>
                <div class="card-body">
                    <canvas id="paisesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Tipos de Contenido</h5>
                </div>
                <div class="card-body">
                    <canvas id="contenidoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-table"></i> Detalles del Período Seleccionado</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="reportTable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nuevos Usuarios</th>
                            <th>Nuevas Publicaciones</th>
                            <th>Total Likes</th>
                            <th>Usuarios Activos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detallesDiarios ?? [] as $dia): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($dia['fecha'])) ?></td>
                            <td><?= $dia['nuevosUsuarios'] ?? 0 ?></td>
                            <td><?= $dia['nuevasPublicaciones'] ?? 0 ?></td>
                            <td><?= $dia['totalLikes'] ?? 0 ?></td>
                            <td><?= $dia['usuariosActivos'] ?? 0 ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top 10 -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Top 10 Usuarios Más Activos</h5>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <?php foreach ($topUsuariosActivos ?? [] as $user): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <strong>@<?= htmlspecialchars($user['username']) ?></strong>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($user['Nombre']) ?></small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <?= $user['totalPublicaciones'] ?> posts
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-fire"></i> Top 10 Publicaciones Más Populares</h5>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        <?php foreach ($topPublicaciones ?? [] as $post): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <strong><?= htmlspecialchars(substr($post['texto'], 0, 50)) ?>...</strong>
                                <br>
                                <small class="text-muted">Por @<?= htmlspecialchars($post['username']) ?></small>
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                <i class="fas fa-heart"></i> <?= $post['likes'] ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de países
new Chart(document.getElementById('paisesChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($paisesLabels ?? ['México', 'Colombia', 'Argentina', 'España']) ?>,
        datasets: [{
            label: 'Usuarios por País',
            data: <?= json_encode($paisesData ?? [45, 30, 15, 10]) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Gráfico de contenido
new Chart(document.getElementById('contenidoChart'), {
    type: 'doughnut',
    data: {
        labels: ['Solo Texto', 'Con Imagen', 'Con Video'],
        datasets: [{
            data: <?= json_encode($contenidoData ?? [60, 30, 10]) ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Función para exportar a CSV
function exportarCSV() {
    const table = document.getElementById('reportTable');
    let csv = [];
    
    // Headers
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent);
    csv.push(headers.join(','));
    
    // Rows
    table.querySelectorAll('tbody tr').forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent);
        csv.push(cells.join(','));
    });
    
    // Download
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `reporte_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
}
</script>

<?php require 'partials/footer.php'; ?>