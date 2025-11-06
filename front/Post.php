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
document.addEventListener("DOMContentLoaded", () => {

  // ==== LIKE SYSTEM ====
  const likeButtons = document.querySelectorAll('.btn-accion');
  likeButtons.forEach(btn => {
    btn.addEventListener('click', async function (e) {
      e.preventDefault();
      e.stopPropagation(); // evita que abra el modal

      const postId = this.dataset.postId;
      const likeCount = this.querySelector('.like-count');
      if (!postId) return;

      try {
        const formData = new FormData();
        formData.append('postId', postId);

        const res = await fetch('/Post/like', {
          method: 'POST',
          body: formData
        });

        if (!res.ok) {
          if (res.status === 401) {
            alert('Debes iniciar sesión para dar like');
            return;
          }
          throw new Error('Error al procesar like');
        }

        const data = await res.json();
        if (data.success) {
          likeCount.textContent = data.likes;
          this.classList.toggle('liked', data.accion === 'liked');
        }
      } catch (err) {
        console.error('Error de conexión:', err);
      }
    });
  });


  // ==== MODAL DETALLE ====
  const modal = document.getElementById('modal-post');
  const modalBody = document.getElementById('modal-body');
  const modalClose = document.querySelector('.zzz-close');

  document.querySelectorAll('.zzz-card').forEach(card => {
    card.addEventListener('click', async e => {
      // Evita abrir si clickeas el botón de like
      if (e.target.closest('.btn-accion')) return;
      const postId = card.dataset.postId;
      if (!postId) return;

      try {
        const res = await fetch(`/Post/${postId}`);
        if (!res.ok) throw new Error('Error al cargar la publicación');
        const post = await res.json();

        modalBody.innerHTML = `
          ${post.rutamulti ? (
            post.tipoContenido === 'imagen'
              ? `<img src="${post.rutamulti}" alt="Imagen">`
              : `<video controls><source src="${post.rutamulti}" type="video/mp4"></video>`
          ) : '<div style="background:#333;padding:3rem;text-align:center;">Sin multimedia</div>'}
          <h3 class="mt-3">${post.username}</h3>
          <p>${post.texto}</p>
          <p><strong>${post.likes}</strong> Likes</p>
          <button class="btn btn-secondary mt-2" onclick="window.location.href='/publicaciones'">Ver más publicaciones</button>
        `;

        modal.style.display = 'flex';
      } catch (err) {
        console.error(err);
        alert('Error al abrir publicación.');
      }
    });
  });

  // Cerrar modal
  modalClose.addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', e => { if (e.target.id === 'modal-post') modal.style.display = 'none'; });
}

);
// === LAZY LOAD ===
document.addEventListener("DOMContentLoaded", () => {
  const grid = document.querySelector('.zzz-grid');
  const trigger = document.getElementById('load-more-trigger');

  if (!grid || !trigger) return;

  let currentPage = <?= $currentPage ?>;
  const totalPages = <?= $totalPages ?>;
  let isLoading = false;

  const loadMore = async () => {
    if (isLoading || currentPage >= totalPages) return;
    isLoading = true;

    const nextPage = currentPage + 1;
    const url = new URL(window.location.href);
    url.searchParams.set('page', nextPage);

    try {
      const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();

      // Extraer solo las nuevas tarjetas
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newCards = doc.querySelectorAll('.zzz-card');

      newCards.forEach(card => grid.appendChild(card));
      currentPage++;
    } catch (err) {
      console.error('❌ Error al cargar más publicaciones:', err);
    } finally {
      isLoading = false;
    }
  };

  // Usamos IntersectionObserver para detectar cuando llega al final
  const observer = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) {
      loadMore();
    }
  }, { rootMargin: '200px' });

  observer.observe(trigger);
});

</script>

<?php require 'partials/footer.php'; ?>
