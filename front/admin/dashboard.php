<?php require 'partials/header.php'; ?>

<div class="container-fluid">
    <h2 class="mb-4">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </h2>

    <!-- Tarjetas de Estadísticas -->
    <div class="row g-4 mb-4">

        <!-- Total Usuarios -->
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Total Usuarios</h6>
                            <h2 class="card-title mb-0"><?= $stats['totalUsuarios'] ?? 0 ?></h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                    <small>+<?= $stats['nuevosHoy'] ?? 0 ?> hoy</small>
                </div>
            </div>
        </div>

        <!-- Publicaciones -->
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Publicaciones</h6>
                            <h2 class="card-title mb-0"><?= $stats['totalPublicaciones'] ?? 0 ?></h2>
                        </div>
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
                    </div>
                    <small>+<?= $stats['publicacionesHoy'] ?? 0 ?> hoy</small>
                </div>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Usuarios Activos</h6>
                            <h2 class="card-title mb-0"><?= $stats['usuariosActivos'] ?? 0 ?></h2>
                        </div>
                        <i class="fas fa-user-check fa-3x opacity-50"></i>
                    </div>
                    <small>Última semana</small>
                </div>
            </div>
        </div>

        <!-- Contenido Oculto -->
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Contenido Oculto</h6>
                            <h2 class="card-title mb-0"><?= $stats['contenidoOculto'] ?? 0 ?></h2>
                        </div>
                        <i class="fas fa-eye-slash fa-3x opacity-50"></i>
                    </div>
                    <small>Requiere revisión</small>
                </div>
            </div>
        </div>

        <!-- 💬 Comentarios Totales -->
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Comentarios Totales</h6>
                            <h2 class="card-title mb-0"><?= $stats['totalComentarios'] ?? 0 ?></h2>
                        </div>
                        <i class="fas fa-comments fa-3x opacity-50"></i>
                    </div>
                    <small>Actualizados automáticamente</small>
                </div>
            </div>
        </div>

    </div>

    <!-- 📈 Gráfico de Actividad -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Actividad de los últimos 7 días</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- 🌟 Top Usuarios -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star"></i> Top Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($topUsers ?? [] as $index => $user): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; line-height: 20px;">
                                    <?= $index + 1 ?>
                                </span>
                                <div>
                                    <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= $user['postCount'] ?> publicaciones</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🕒 Actividad Reciente -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentActivity ?? [] as $activity): ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <?= htmlspecialchars($activity['username']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($activity['action']) ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($activity['fecha'])) ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de actividad
const ctx = document.getElementById('activityChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels ?? ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']) ?>,
        datasets: [{
            label: 'Nuevos Usuarios',
            data: <?= json_encode($chartUsuarios ?? [5, 8, 12, 7, 15, 10, 9]) ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Nuevas Publicaciones',
            data: <?= json_encode($chartPublicaciones ?? [15, 22, 18, 25, 30, 28, 32]) ?>,
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>

<?php require 'partials/footer.php'; ?>
