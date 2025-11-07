<?php require 'partials/header.php'; ?>

<div class="contenedor">
    <div class="header-seccion">
        <h2 class="titulo-seccion">✍️ Crear Nueva Publicación</h2>
        <a href="/public" class="btn-volver">← Volver a publicaciones</a>
    </div>
    
    <form id="formPublicacion" enctype="multipart/form-data" class="form-publicacion">
        
        <!-- Texto de la publicación -->
        <div class="form-group">
            <label for="texto">
                <i>💭</i> ¿Qué estás pensando?
            </label>
            <textarea 
                id="texto" 
                name="texto" 
                rows="5" 
                maxlength="500" 
                placeholder="Comparte tus opiniones sobre el Mundial, tu equipo favorito, momentos históricos..."
                required
            ></textarea>
            <small class="contador-caracteres">0/500 caracteres</small>
        </div>

        <!-- Categoría -->
        <div class="form-group">
            <label for="categoria">
                <i>📂</i> Categoría
            </label>
            <select name="idCategoria" id="categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['idCategoria'] ?>">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo de contenido -->
        <div class="form-group">
            <label><i>🎨</i> Tipo de contenido:</label>
            <div class="tipo-contenido">
                <label class="radio-card">
                    <input type="radio" name="tipo" value="texto" checked>
                    <span class="radio-content">
                        <span class="radio-icon">📝</span>
                        <span class="radio-text">Solo texto</span>
                    </span>
                </label>
                <label class="radio-card">
                    <input type="radio" name="tipo" value="imagen">
                    <span class="radio-content">
                        <span class="radio-icon">🖼️</span>
                        <span class="radio-text">Con imagen</span>
                    </span>
                </label>
                <label class="radio-card">
                    <input type="radio" name="tipo" value="video">
                    <span class="radio-content">
                        <span class="radio-icon">🎥</span>
                        <span class="radio-text">Con video</span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Upload de imagen -->
        <div class="form-group archivo-upload" id="imagenUpload" style="display:none;">
            <label for="imagen">
                <i>🖼️</i> Subir Imagen
            </label>
            <div class="upload-area">
                <input 
                    type="file" 
                    id="imagen" 
                    name="imagen" 
                    accept="image/jpeg,image/png,image/gif,image/webp"
                >
                <div class="upload-placeholder">
                    <span class="upload-icon">📸</span>
                    <p>Arrastra una imagen aquí o haz clic para seleccionar</p>
                    <small>JPG, PNG, GIF o WEBP • Máximo 5MB</small>
                </div>
            </div>
            <div class="preview" id="imagenPreview"></div>
        </div>

        <!-- Upload de video -->
        <div class="form-group archivo-upload" id="videoUpload" style="display:none;">
            <label for="video">
                <i>🎥</i> Subir Video
            </label>
            <div class="upload-area">
                <input 
                    type="file" 
                    id="video" 
                    name="video" 
                    accept="video/mp4,video/quicktime,video/x-msvideo"
                >
                <div class="upload-placeholder">
                    <span class="upload-icon">🎬</span>
                    <p>Arrastra un video aquí o haz clic para seleccionar</p>
                    <small>MP4, MOV o AVI • Máximo 50MB</small>
                </div>
            </div>
            <div class="preview" id="videoPreview"></div>
        </div>

        <!-- Botones de acción -->
        <div class="form-actions">
            <button type="submit" class="btn-publicar">
                <span class="btn-icon">✓</span>
                <span class="btn-text">Publicar</span>
            </button>
            <a href="/public" class="btn-cancelar">
                <span class="btn-icon">✕</span>
                <span class="btn-text">Cancelar</span>
            </a>
        </div>

        <!-- Mensaje de respuesta -->
        <div id="mensaje" class="mensaje" style="display:none;"></div>
    </form>
</div>




<script src="/js/crearpost.js"></script>
<?php require 'partials/footer.php'; ?>
