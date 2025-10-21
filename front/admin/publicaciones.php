<?php require 'partials/header.php'; ?>

<div class="container-fluid">
    <h2 class="mb-4">Gestión de Publicaciones</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); // Limpiar el mensaje ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Todas las Publicaciones (Total: <?= $total ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Autor</th>
                            <th>Contenido</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($publicaciones)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay publicaciones para mostrar.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($publicaciones as $post): ?>
                            <tr>
                                <td><strong><?= $post['idPublicacion'] ?></strong></td>
                                <td><?= htmlspecialchars($post['username'] ?? 'Usuario eliminado') ?></td>
                                <td style="max-width: 300px;">
                                    <span class="d-inline-block text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($post['texto']) ?>">
                                        <?= htmlspecialchars($post['texto']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($post['tipoContenido']): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($post['tipoContenido']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">Texto</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($post['postdate'])) ?></td>
                                <td>
                                    <?php if ($post['estado'] === 'publico'): ?>
                                        <span class="badge bg-success">Público</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= htmlspecialchars($post['estado']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ($post['estado'] === 'publico'): ?>
                                        <form action="/admin/publicaciones/<?= $post['idPublicacion'] ?>/ocultar" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-warning btn-sm" title="Ocultar publicación">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="/admin/publicaciones/<?= $post['idPublicacion'] ?>/mostrar" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-success btn-sm" title="Mostrar publicación">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                   <a href="/Post/<?= $post['idPublicacion'] ?>" class="btn btn-info btn-sm" title="Ver" target="_blank">
    <i class="fas fa-external-link-alt"></i>
</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            </div>
    </div>
</div>

<?php require 'partials/footer.php'; ?>