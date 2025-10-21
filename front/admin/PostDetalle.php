<?php require 'partials/header.php'; ?>

<div class="contenedor mt-5">
    <a href="/Post" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Publicaciones
    </a>

    <div class="card shadow">
        <div class="card-header d-flex align-items-center gap-3">
            <?php if ($post['fotoPerfil']): ?>
                <img src="<?= htmlspecialchars($post['fotoPerfil']) ?>" 
                     alt="Avatar" 
                     class="rounded-circle" 
                     style="width: 50px; height: 50px; object-fit: cover;">
            <?php else: ?>
                <div class="user-avatar" style="width: 50px; height: 50px; font-size: 1.5rem;">
                    <?= strtoupper(substr($post['username'] ?? 'U', 0, 1)) ?>
                </div>
            <?php endif; ?>
            
            <div>
                <h5 class="mb-0"><?= htmlspecialchars($post['Nombre'] ?? $post['username']) ?></h5>
                <small class="text-muted">@<?= htmlspecialchars($post['username']) ?></small>
                <br>
                <small class="text-muted">
                    <i class="fas fa-clock"></i> 
                    <?= date('d/m/Y H:i', strtotime($post['postdate'])) ?>
                </small>
            </div>
        </div>

        <div class="card-body">
            <p class="card-text" style="font-size: 1.1rem; white-space: pre-wrap;">
                <?= nl2br(htmlspecialchars($post['texto'])) ?>
            </p>

            <?php if ($post['tipoContenido'] === 'imagen' && $post['rutamulti']): ?>
                <div class="mt-3">
                    <img src="/<?= htmlspecialchars($post['rutamulti']) ?>" 
                         alt="Imagen de publicación" 
                         class="img-fluid rounded"
                         style="max-height: 600px; width: auto;">
                </div>
            <?php elseif ($post['tipoContenido'] === 'video' && $post['rutamulti']): ?>
                <div class="mt-3">
                    <video controls class="w-100 rounded" style="max-height: 600px;">
                        <source src="/<?= htmlspecialchars($post['rutamulti']) ?>" type="video/mp4">
                        Tu navegador no soporta videos.
                    </video>
                </div>
            <?php endif; ?>

            <div class="mt-4 d-flex gap-3">
                <button class="btn btn-outline-primary" id="btn-like" data-post-id="<?= $post['idPublicacion'] ?>">
                    <i class="fas fa-heart"></i> 
                    <span id="like-count"><?= $post['likes'] ?? 0 ?></span> Me gusta
                </button>
                
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-share"></i> Compartir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-like')?.addEventListener('click', async function() {
    const postId = this.dataset.postId;
    try {
        const response = await fetch(`/Post/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ idPublicacion: postId })
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('like-count').textContent = data.likes;
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>

<?php require 'partials/footer.php'; ?>