<?php require 'partials/header.php'; ?>

<div class="contenedor contenido-principal">
    <h2 class="titulo-seccion">Publicaciones Destacadas</h2>

    <div class="slider-container">
        <?php 
        // Mostramos las primeras 3 publicaciones como destacadas
        $destacadas = array_slice($publicaciones, 0, 3);
        foreach ($destacadas as $index => $post): 
        ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>">
            <div class="destacado-post">
                <div class="form-group">
                    <h3><?= htmlspecialchars($post['Nombre'] ?? $post['username']) ?></h3>
                    <p><?= htmlspecialchars($post['texto']) ?></p>
                    
                    <button type="button" class="btn-accion" data-post-id="<?= $post['idPublicacion'] ?>">
                        <img src="imagenes/like.png" alt="Like">
                        <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
                    </button>
                </div>
                
                <?php if ($post['tipoContenido'] === 'imagen' && $post['rutamulti']): ?>
                <div class="form-group media-estatica">
                    <img src="<?= htmlspecialchars($post['rutamulti']) ?>" alt="Imagen">
                </div>
                <?php elseif ($post['tipoContenido'] === 'video' && $post['rutamulti']): ?>
                <div class="form-group media-estatica">
                    <video controls src="<?= htmlspecialchars($post['rutamulti']) ?>"></video>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="slider-nav">
        <?php foreach ($destacadas as $index => $post): ?>
        <button class="dot <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>"></button>
        <?php endforeach; ?>
    </div>
</div>

<div class="contenedor">
    <h2 class="titulo-seccion">Más Publicaciones</h2>
    <div class="otros-posts">
        <?php 
        // Mostramos el resto de publicaciones
        $otrasPublicaciones = array_slice($publicaciones, 3);
        foreach ($otrasPublicaciones as $post): 
        ?>
        <div class="producto">
            <div class="form-group">
                <h3><?= htmlspecialchars($post['Nombre'] ?? $post['username']) ?></h3>
                <p><?= htmlspecialchars(substr($post['texto'], 0, 100)) ?><?= strlen($post['texto']) > 100 ? '...' : '' ?></p>
                
                <?php if ($post['tipoContenido'] === 'imagen' && $post['rutamulti']): ?>
                <div class="media-containerS">
                    <img src="<?= htmlspecialchars($post['rutamulti']) ?>" alt="Imagen">
                </div>
                <?php elseif ($post['tipoContenido'] === 'video' && $post['rutamulti']): ?>
                <div class="media-containerS">
                    <video controls src="<?= htmlspecialchars($post['rutamulti']) ?>"></video>
                </div>
                <?php endif; ?>
                
                <button type="button" class="btn-accion" data-post-id="<?= $post['idPublicacion'] ?>">
                    <img src="imagenes/like.png" alt="Like">
                    <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function showSlide(slideIndex) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        slides[slideIndex].classList.add('active');
        dots[slideIndex].classList.add('active');
    }

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            const slideIndex = parseInt(dot.dataset.slide);
            showSlide(slideIndex);
        });
    });

    // Manejar likes con AJAX
    document.querySelectorAll('.btn-accion').forEach(btn => {
        btn.addEventListener('click', async function() {
            const postId = this.dataset.postId;
            try {
                const response = await fetch(`/Post/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.querySelector('.like-count').textContent = data.likes;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>

<?php require 'partials/footer.php'; ?>