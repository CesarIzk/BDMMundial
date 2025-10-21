<?php require 'partials/header.php'; ?>

<div class="contenedor mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users-cog"></i> Gestión de Usuarios</h2>
        <a href="/" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>País</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['idUsuario']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['Nombre']); ?></td>
                            <td>@<?php echo htmlspecialchars($usuario['username']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['pais'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $usuario['rol'] === 'admin' ? 'danger' : 'primary'; ?>">
                                    <?php echo ucfirst($usuario['rol']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $usuario['estado'] === 'activo' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($usuario['estado']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['fechaRegistro'])); ?></td>
                            <td>
                                <?php if ($usuario['idUsuario'] !== $_SESSION['user']['idUsuario']): ?>
                                    <?php if ($usuario['estado'] === 'activo'): ?>
                                        <form method="POST" action="/admin/usuario/<?php echo $usuario['idUsuario']; ?>/baja" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Desactivar este usuario?')">
                                                <i class="fas fa-ban"></i> Desactivar
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="/admin/usuario/<?php echo $usuario['idUsuario']; ?>/activar" style="display:inline;">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Activar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Eres tú</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 text-muted">
        <small>Total de usuarios: <?php echo count($usuarios); ?></small>
    </div>
</div>
<?php require 'partials/footer.php'; ?>
