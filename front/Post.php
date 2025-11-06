<?php require 'partials/header.php'; ?>

<div class="contenedor mt-5">
  <!-- 🧠 Indicador de búsqueda -->
  <?php if (!empty($busqueda)): ?>
    <p class="text-center text-muted mb-4">
      Mostrando resultados para "<strong><?= htmlspecialchars($busqueda) ?></strong>"
      (<?= $total ?> resultados)
    </p>
  <?php endif; ?>

  <!-- 🧩 Buscador -->
  <form class="d-flex flex-wrap justify-content-center gap-3 mb-4" method="GET" action="/publicaciones">
    <input 
      type="text" 
      name="q" 
      class="form-control" 
      placeholder="Buscar publicaciones..." 
      value="<?= htmlspecialchars($busqueda ?? '') ?>" 
      style="max-width:250px;"
    >

    <select name="categoria" class="form-select" style="max-width:220px;">
      <option value="">Todas las categorías</option>
      <?php foreach ($categorias as $cat): ?>
        <option value="<?= $cat['idCategoria'] ?>" <?= ($categoriaSeleccionada == $cat['idCategoria']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="orden" class="form-select" style="max-width:220px;">
      <option value="reciente" <?= $orden === 'reciente' ? 'selected' : '' ?>>Más recientes</option>
      <option value="populares" <?= $orden === 'populares' ? 'selected' : '' ?>>Más populares</option>
    </select>

    <button class="btn btn-primary px-3">
      <i class="fas fa-search"></i> Buscar
    </button>
  </form>
</div>

<!-- 📣 Publicaciones -->
<div class="contenedor py-4">
  <h2 class="titulo-seccion mb-4">📣 Publicaciones</h2>

  <?php if (empty($publicaciones)): ?>
    <p class="text-center text-muted">😕 No se encontraron publicaciones que coincidan con tu búsqueda.</p>
  <?php else: ?>
    
    <!-- 🎮 Feed tipo Zenless Zone Zero -->
    <div class="zzz-grid">
      <?php foreach ($publicaciones as $post): ?>
        <div class="zzz-card" data-post-id="<?= $post['idPublicacion'] ?>">
          
          <!-- 🖼️ Imagen o video -->
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

          <!-- ❤️ Likes -->
          <div class="zzz-like btn-accion" data-post-id="<?= $post['idPublicacion'] ?>">
            <img src="imagenes/like.png" alt="Like">
            <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
          </div>

          <!-- 🧾 Texto y usuario -->
          <div class="zzz-overlay">
            <div class="zzz-title">
              <?= htmlspecialchars(substr($post['texto'], 0, 60)) ?><?= strlen($post['texto']) > 60 ? '...' : '' ?>
            </div>
            <div class="zzz-user">
              @<?= htmlspecialchars($post['username']) ?> · <?= htmlspecialchars($post['categoriaNombre'] ?? 'General') ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- 📄 Paginación -->
  <div class="text-center mt-4">
    <?php if ($currentPage > 1): ?>
      <a href="?q=<?=urlencode($busqueda)?>&categoria=<?=$categoriaSeleccionada?>&orden=<?=$orden?>&page=<?=$currentPage-1?>" class="btn btn-outline-secondary">← Anterior</a>
    <?php endif; ?>
    <?php if ($currentPage < $totalPages): ?>
      <a href="?q=<?=urlencode($busqueda)?>&categoria=<?=$categoriaSeleccionada?>&orden=<?=$orden?>&page=<?=$currentPage+1?>" class="btn btn-outline-secondary">Siguiente →</a>
    <?php endif; ?>
  </div>
</div>
<!-- 🔽 Marcador de final -->
<div id="load-more-trigger" style="height: 40px;"></div>
<!-- 🌌 MODAL DE PUBLICACIÓN -->
<div id="modal-post" class="zzz-modal">
  <div class="zzz-modal-content">
    <button class="zzz-close">&times;</button>
    <div id="modal-body"></div>
  </div>
</div>

<script>
// ==== MODAL DETALLE ==== (VERSIÓN MEJORADA)
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById('modal-post');
  const modalBody = document.getElementById('modal-body');
  const modalClose = document.querySelector('.zzz-close');

  document.querySelectorAll('.zzz-card').forEach(card => {
    card.addEventListener('click', async e => {
      // Evita abrir si clickeas el botón de like
      if (e.target.closest('.btn-accion')) return;
      
      const postId = card.dataset.postId;
      if (!postId) return;

      // Mostrar loading
      modalBody.innerHTML = '<div style="text-align:center;padding:3rem;"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';
      modal.style.display = 'flex';

      try {
        // 🔧 OPCIÓN A: Intenta primero con query params
        let res = await fetch(`/Post/view?id=${postId}`);
        
        // 🔧 OPCIÓN B: Si falla, intenta con la ruta dinámica
        if (!res.ok) {
          res = await fetch(`/Post/${postId}`);
        }
        
        if (!res.ok) {
          throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }
        
        const post = await res.json();

        // Construir contenido del modal
        let mediaHTML = '';
        if (post.rutamulti) {
          if (post.tipoContenido === 'imagen') {
            mediaHTML = `<img src="${post.rutamulti}" alt="Imagen" style="max-width:100%;height:auto;border-radius:8px;">`;
          } else {
            mediaHTML = `<video controls style="max-width:100%;height:auto;border-radius:8px;">
                          <source src="${post.rutamulti}" type="video/mp4">
                        </video>`;
          }
        } else {
          mediaHTML = '<div style="background:#333;padding:3rem;text-align:center;border-radius:8px;">Sin multimedia</div>';
        }

        modalBody.innerHTML = `
          ${mediaHTML}
          <div style="padding:1.5rem 0;">
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
              ${post.fotoPerfil ? `<img src="${post.fotoPerfil}" alt="${post.username}" style="width:50px;height:50px;border-radius:50%;">` : ''}
              <div>
                <h3 style="margin:0;">${post.Nombre || post.username}</h3>
                <p style="margin:0;color:#888;">@${post.username}</p>
              </div>
            </div>
            <p style="font-size:1.1rem;line-height:1.6;">${post.texto}</p>
            <div style="display:flex;gap:2rem;margin-top:1rem;color:#888;">
              <span><i class="fas fa-heart"></i> <strong>${post.likes}</strong> Likes</span>
              <span><i class="fas fa-tag"></i> ${post.categoriaNombre || 'Sin categoría'}</span>
            </div>
          </div>
          <button class="btn btn-primary mt-3" onclick="window.location.href='/publicaciones'">
            Ver más publicaciones
          </button>
        `;

      } catch (err) {
        console.error('❌ Error al cargar publicación:', err);
        modalBody.innerHTML = `
          <div style="text-align:center;padding:3rem;">
            <i class="fas fa-exclamation-triangle fa-3x" style="color:#e74c3c;"></i>
            <h3 style="margin-top:1rem;">Error al cargar la publicación</h3>
            <p style="color:#888;">${err.message}</p>
            <button class="btn btn-secondary mt-3" onclick="document.getElementById('modal-post').style.display='none'">
              Cerrar
            </button>
          </div>
        `;
      }
    });
  });

  // Cerrar modal
  modalClose.addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', e => { 
    if (e.target.id === 'modal-post') modal.style.display = 'none'; 
  });
});
</script>

<?php require 'partials/footer.php'; ?>