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
// ==== MODAL DETALLE CON COMENTARIOS ====
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
        // 1️⃣ Cargar publicación
        let res = await fetch(`/public/view?id=${postId}`);
        if (!res.ok) res = await fetch(`/public/${postId}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        const post = await res.json();

        // 2️⃣ Cargar comentarios
       const comentariosRes = await fetch(`/api/comentarios/${post.idPublicacion}`);

        const comentarios = comentariosRes.ok ? await comentariosRes.json() : [];

        // === MEDIOS ===
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

        // === COMENTARIOS ===
        let comentariosHTML = '';
        if (comentarios.length > 0) {
          comentariosHTML = `
            <div class="mt-4">
              <h5><i class="fas fa-comments"></i> Comentarios (${comentarios.length})</h5>
              <ul class="list-group mt-3">
                ${comentarios.map(c => `
                  <li class="list-group-item">
  <strong>@${c.username || 'Anónimo'}</strong>
  <p class="mb-1">${c.contenido || ''}</p>
  <small class="text-muted">${c.fecha || ''}</small>
</li>

                `).join('')}
              </ul>
            </div>
          `;
        } else {
          comentariosHTML = `
            <div class="mt-4 text-center text-muted">
              <i class="fas fa-comment-slash fa-2x"></i>
              <p class="mt-2">Sin comentarios aún.</p>
            </div>
          `;
        }

        // === FORMULARIO DE NUEVO COMENTARIO ===
        comentariosHTML += `
          <form id="form-comentario" class="mt-4">
            <textarea id="textoComentario" class="form-control" rows="2" placeholder="Escribe un comentario..."></textarea>
            <button type="submit" class="btn btn-secondary mt-2">
              <i class="fas fa-paper-plane"></i> Enviar comentario
            </button>
          </form>
        `;

        // === ESTRUCTURA COMPLETA DEL MODAL ===
        modalBody.innerHTML = `
          ${mediaHTML}
          <div class="mt-4">
            <div class="d-flex align-items-center gap-3 mb-3">
              ${post.fotoPerfil ? `<img src="${post.fotoPerfil}" alt="${post.username}" class="rounded-circle" style="width:50px;height:50px;">` : ''}
              <div>
                <h4 class="mb-0">${post.Nombre || post.username}</h4>
                <p class="text-muted mb-0">@${post.username}</p>
              </div>
            </div>
            <p style="font-size:1.1rem;line-height:1.6;">${post.texto}</p>
            <div class="d-flex gap-4 text-muted mt-3">
              <span><i class="fas fa-heart"></i> <strong>${post.likes}</strong> Likes</span>
              <span><i class="fas fa-tag"></i> ${post.categoriaNombre || 'Sin categoría'}</span>
            </div>
          </div>
          ${comentariosHTML}
        `;

        // === MANEJAR ENVÍO DEL NUEVO COMENTARIO ===
        const formComentario = document.getElementById('form-comentario');
        formComentario.addEventListener('submit', async e => {
          e.preventDefault();
          const texto = document.getElementById('textoComentario').value.trim();
          if (!texto) return alert('Escribe un comentario antes de enviar.');

          const resp = await fetch('/api/comentarios', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idPublicacion=${post.idPublicacion}&contenido=${encodeURIComponent(texto)}`
          });

          if (resp.ok) {
            const nuevo = await resp.json();
            const lista = formComentario.previousElementSibling.querySelector('ul');
            if (lista) {
              lista.innerHTML += `
                <li class="list-group-item">
                  <strong>@${nuevo.username}</strong>
                  <p class="mb-1">${nuevo.texto}</p>
                  <small class="text-muted">${nuevo.fechaComentario}</small>
                </li>
              `;
            }
            formComentario.reset();
          } else {
            alert('❌ No se pudo enviar el comentario.');
          }
        });

      } catch (err) {
        console.error('❌ Error al cargar publicación:', err);
        modalBody.innerHTML = `
          <div style="text-align:center;padding:3rem;">
            <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
            <h3 class="mt-3">Error al cargar la publicación</h3>
            <p class="text-muted">${err.message}</p>
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