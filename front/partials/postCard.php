<div class="zzz-card" data-post-id="<?= $post['idPublicacion'] ?>">
  <?php if ($post['rutamulti']): ?>
    <?php if ($post['tipoContenido'] === 'imagen'): ?>
      <img src="<?= htmlspecialchars($post['rutamulti']) ?>" class="zzz-media" alt="Imagen de publicación">
    <?php else: ?>
      <video class="zzz-media" autoplay loop muted>
        <source src="<?= htmlspecialchars($post['rutamulti']) ?>" type="video/mp4">
      </video>
    <?php endif; ?>
  <?php else: ?>
    <div class="zzz-media" style="background:#444;display:flex;align-items:center;justify-content:center;color:#fff;">
      Sin multimedia
    </div>
  <?php endif; ?>

  <div class="zzz-like btn-accion" data-post-id="<?= $post['idPublicacion'] ?>">
    <img src="imagenes/like.png" alt="Like">
    <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
  </div>

  <div class="zzz-overlay">
    <div class="zzz-title">
      <?= htmlspecialchars(substr($post['texto'], 0, 60)) ?><?= strlen($post['texto']) > 60 ? '...' : '' ?>
    </div>
    <div class="zzz-user">
      @<?= htmlspecialchars($post['username']) ?> · <?= htmlspecialchars($post['categoriaNombre'] ?? 'General') ?>
    </div>
  </div>
</div>
<div id="load-more-trigger">
  <div id="loading-spinner" style="display:none; text-align:center; padding:1rem;">
    <i class="fas fa-circle-notch fa-spin"></i> Cargando más publicaciones...
  </div>
</div>
