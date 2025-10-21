<?php require 'partials/header.php'; ?>

<div class="contenedor contenido-principal">
    <h2 class="titulo-seccion">Publicaciones Destacadas</h2>

    <div class="slider-container">
        <?php 
        $destacadas = array_slice($publicaciones, 0, 3);
        foreach ($destacadas as $index => $post): 
        ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?>">
            
            <div class="destacado-post">
                
                <div class="destacado-contenido">
                    <h2><?= htmlspecialchars($post['Nombre'] ?? $post['username']) ?></h2>
                    <p><?= htmlspecialchars($post['texto']) ?></p>
                    
                    <button type="button" class="btn-accion" data-post-id="<?= $post['idPublicacion'] ?>">
                        <img src="imagenes/like.png" alt="Like">
                        <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
                    </button>
                </div>
                
                <?php if ($post['tipoContenido'] === 'imagen' && $post['rutamulti']): ?>
                <div class="destacado-media"> <img src="<?= htmlspecialchars($post['rutamulti']) ?>" alt="Imagen">
                </div>
                <?php elseif ($post['tipoContenido'] === 'video' && $post['rutamulti']): ?>
                <div class="destacado-media"> <video controls src="<?= htmlspecialchars($post['rutamulti']) ?>"></video>
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
    <div class="otros-posts"> <?php 
        $otrasPublicaciones = array_slice($publicaciones, 3);
        foreach ($otrasPublicaciones as $post): 
        ?>
        
        <div class="post-card"> 
            
            <?php // ----- SECCIÓN DE IMAGEN/VIDEO ----- ?>
            <?php if ($post['tipoContenido'] === 'imagen' && $post['rutamulti']): ?>
            <div class="post-media"> <img src="<?= htmlspecialchars($post['rutamulti']) ?>" alt="Imagen">
            </div>
            <?php elseif ($post['tipoContenido'] === 'video' && $post['rutamulti']): ?>
            <div class="post-media"> <video controls src="<?= htmlspecialchars($post['rutamulti']) ?>"></video>
            </div>
            <?php endif; ?>

            <?php // ----- SECCIÓN DE CONTENIDO ----- ?>
            <div class="post-content"> 
                
                <div class="post-header">
                    <div class="post-autor">
                        <div class="post-autor-info">
                            <h4><?= htmlspecialchars($post['Nombre'] ?? $post['username']) ?></h4>
                        </div>
                    </div>
                </div>

                <p class="post-descripcion">
                    <?= htmlspecialchars(substr($post['texto'], 0, 100)) ?><?= strlen($post['texto']) > 100 ? '...' : '' ?>
                </p>
                
                <div class="post-actions"> 
                    <button type="button" data-post-id="<?= $post['idPublicacion'] ?>">
                        <img src="imagenes/like.png" alt="Like" style="width:16px; height:16px;">
                        <span class="like-count"><?= $post['likes'] ?? 0 ?></span>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Manejar likes con AJAX
document.querySelectorAll('.post-actions button').forEach(btn => { // CAMBIO: Selector actualizado
    btn.addEventListener('click', async function() {
        // ... (el resto de tu script está bien)
        const postId = this.dataset.postId;
        const likeCount = this.querySelector('.like-count');
        
        try {
            const response = await fetch('/Post/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `postId=${postId}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                likeCount.textContent = data.likes;
                if (data.accion === 'liked') {
                    this.classList.add('liked');
                } else {
                    this.classList.remove('liked');
                }
            } else {
                alert(data.error || 'Error al procesar like');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión');
        }
    });
});
</script>

<?php require 'partials/footer.php'; ?>