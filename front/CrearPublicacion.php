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

<style>
/* === CONTENEDOR PRINCIPAL === */
.contenedor {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.header-seccion {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.titulo-seccion {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.btn-volver {
    padding: 0.5rem 1rem;
    background: transparent;
    border: 1px solid #ddd;
    border-radius: 6px;
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-volver:hover {
    background: #f5f5f5;
    border-color: #999;
    color: #333;
}

/* === FORMULARIO === */
.form-publicacion {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.form-group {
    margin-bottom: 1.75rem;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.form-group label i {
    font-style: normal;
}

/* === TEXTAREA === */
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1rem;
    line-height: 1.5;
    resize: vertical;
    background: #fafafa;
    color: #1a1a1a;
    transition: all 0.3s;
}

.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group textarea::placeholder {
    color: #999;
}

/* === SELECT === */
.form-select {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    background: #fafafa;
    color: #1a1a1a;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
}

.form-select:focus {
    outline: none;
    border-color: #007bff;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

/* === CONTADOR === */
.contador-caracteres {
    display: block;
    margin-top: 0.5rem;
    color: #666;
    font-size: 0.875rem;
    text-align: right;
}

/* === TIPO DE CONTENIDO (RADIO CARDS) === */
.tipo-contenido {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.radio-card {
    position: relative;
    cursor: pointer;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.25rem;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    background: #fafafa;
    transition: all 0.3s;
}

.radio-icon {
    font-size: 2rem;
}

.radio-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: #666;
}

.radio-card input[type="radio"]:checked + .radio-content {
    border-color: #007bff;
    background: #e7f3ff;
}

.radio-card input[type="radio"]:checked + .radio-content .radio-text {
    color: #007bff;
    font-weight: 600;
}

.radio-card:hover .radio-content {
    border-color: #007bff;
    background: #f8f9fa;
}

/* === UPLOAD AREA === */
.upload-area {
    position: relative;
}

.upload-area input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.upload-placeholder {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background: #fafafa;
    transition: all 0.3s;
}

.upload-area:hover .upload-placeholder {
    border-color: #007bff;
    background: #f0f8ff;
}

.upload-icon {
    font-size: 3rem;
    display: block;
    margin-bottom: 0.5rem;
}

.upload-placeholder p {
    margin: 0.5rem 0;
    color: #333;
    font-weight: 500;
}

.upload-placeholder small {
    color: #666;
    font-size: 0.85rem;
}

/* === PREVIEW === */
.preview {
    margin-top: 1rem;
}

.preview img,
.preview video {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* === BOTONES === */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-publicar,
.btn-cancelar {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-publicar {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border: none;
}

.btn-publicar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.btn-publicar:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-cancelar {
    background: transparent;
    color: #666;
    border: 2px solid #ddd;
}

.btn-cancelar:hover {
    background: #f5f5f5;
    border-color: #999;
    color: #333;
}

/* === MENSAJES === */
.mensaje {
    margin-top: 1.5rem;
    padding: 1rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mensaje.exito {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.mensaje.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* === MODO OSCURO === */
[data-theme="dark"] .contenedor {
    color: #e5e5e5;
}

[data-theme="dark"] .titulo-seccion {
    color: #f5f5f5;
}

[data-theme="dark"] .btn-volver {
    border-color: #444;
    color: #bbb;
}

[data-theme="dark"] .btn-volver:hover {
    background: #2a2a2a;
    border-color: #666;
    color: #fff;
}

[data-theme="dark"] .form-publicacion {
    background: #1e1e1e;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
}

[data-theme="dark"] .form-group label {
    color: #ddd;
}

[data-theme="dark"] textarea,
[data-theme="dark"] .form-select {
    background: #2a2a2a;
    border-color: #444;
    color: #f0f0f0;
}

[data-theme="dark"] textarea:focus,
[data-theme="dark"] .form-select:focus {
    border-color: #3399ff;
    background: #2a2a2a;
    box-shadow: 0 0 0 3px rgba(51,153,255,0.2);
}

[data-theme="dark"] textarea::placeholder {
    color: #888;
}

[data-theme="dark"] .contador-caracteres {
    color: #aaa;
}

[data-theme="dark"] .radio-content {
    background: #2a2a2a;
    border-color: #444;
}

[data-theme="dark"] .radio-card input[type="radio"]:checked + .radio-content {
    border-color: #3399ff;
    background: rgba(51, 153, 255, 0.15);
}

[data-theme="dark"] .radio-card input[type="radio"]:checked + .radio-content .radio-text {
    color: #3399ff;
}

[data-theme="dark"] .upload-placeholder {
    background: #2a2a2a;
    border-color: #444;
}

[data-theme="dark"] .upload-area:hover .upload-placeholder {
    border-color: #3399ff;
    background: rgba(51, 153, 255, 0.1);
}

[data-theme="dark"] .upload-placeholder p {
    color: #ddd;
}

[data-theme="dark"] .upload-placeholder small {
    color: #aaa;
}

[data-theme="dark"] .btn-publicar {
    background: linear-gradient(135deg, #3399ff 0%, #1a6ed1 100%);
}

[data-theme="dark"] .btn-cancelar {
    border-color: #555;
    color: #bbb;
}

[data-theme="dark"] .btn-cancelar:hover {
    background: #2e2e2e;
    border-color: #666;
}

[data-theme="dark"] .mensaje.exito {
    background: rgba(56, 142, 60, 0.2);
    color: #b6f5b6;
    border-color: rgba(76, 175, 80, 0.3);
}

[data-theme="dark"] .mensaje.error {
    background: rgba(211, 47, 47, 0.15);
    color: #ffb3b3;
    border-color: rgba(211, 47, 47, 0.3);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .header-seccion {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-publicacion {
        padding: 1.5rem;
    }
    
    .tipo-contenido {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formPublicacion');
    const textarea = document.getElementById('texto');
    const contador = document.querySelector('.contador-caracteres');
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const imagenUpload = document.getElementById('imagenUpload');
    const videoUpload = document.getElementById('videoUpload');
    const imagenInput = document.getElementById('imagen');
    const videoInput = document.getElementById('video');
    const mensaje = document.getElementById('mensaje');

    // Contador de caracteres
    textarea.addEventListener('input', () => {
        const length = textarea.value.length;
        contador.textContent = `${length}/500 caracteres`;
        
        if (length > 450) {
            contador.style.color = '#dc3545';
            contador.style.fontWeight = '600';
        } else if (length > 400) {
            contador.style.color = '#ff9800';
        } else {
            contador.style.color = '#666';
            contador.style.fontWeight = 'normal';
        }
    });

    // Mostrar/ocultar campos según tipo
    tipoRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            imagenUpload.style.display = 'none';
            videoUpload.style.display = 'none';
            imagenInput.value = '';
            videoInput.value = '';
            document.getElementById('imagenPreview').innerHTML = '';
            document.getElementById('videoPreview').innerHTML = '';

            if (radio.value === 'imagen') {
                imagenUpload.style.display = 'block';
            } else if (radio.value === 'video') {
                videoUpload.style.display = 'block';
            }
        });
    });

    // Preview de imagen
    imagenInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const preview = document.getElementById('imagenPreview');
        preview.innerHTML = '';

        if (file) {
            // Validar tamaño
            if (file.size > 5 * 1024 * 1024) {
                alert('La imagen no debe superar los 5MB');
                imagenInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview de video
    videoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const preview = document.getElementById('videoPreview');
        preview.innerHTML = '';

        if (file) {
            // Validar tamaño
            if (file.size > 50 * 1024 * 1024) {
                alert('El video no debe superar los 50MB');
                videoInput.value = '';
                return;
            }

            const video = document.createElement('video');
            video.controls = true;
            video.src = URL.createObjectURL(file);
            preview.appendChild(video);
        }
    });

    // Enviar formulario
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnIcon = submitBtn.querySelector('.btn-icon');
        
        submitBtn.disabled = true;
        btnIcon.textContent = '⏳';
        btnText.textContent = 'Publicando...';
        mensaje.style.display = 'none';

        try {
            console.log('📤 Enviando publicación...');
            
            const response = await fetch('/public/store', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            console.log('📥 Response status:', response.status);

            const contentType = response.headers.get('content-type');
            let data;
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                console.error('❌ Respuesta no es JSON:', text);
                throw new Error('Respuesta del servidor inválida');
            }

            console.log('📦 Data:', data);

            if (response.ok && data.success) {
                mensaje.className = 'mensaje exito';
                mensaje.textContent = '✅ ' + (data.message || '¡Publicación creada exitosamente!');
                mensaje.style.display = 'block';
                
                form.reset();
                contador.textContent = '0/500 caracteres';
                
                setTimeout(() => {
                    window.location.href = '/public';
                }, 1500);
            } else {
                if (response.status === 401) {
                    mensaje.className = 'mensaje error';
                    mensaje.textContent = '🔒 Sesión expirada. Redirigiendo...';
                    mensaje.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    throw new Error(data.error || data.message || 'Error desconocido');
                }
            }
        } catch (error) {
            console.error('❌ Error completo:', error);
            
            let errorMsg = error.message || 'Error de conexión';
            
            // Si hay detalles adicionales en el data
            if (data && data.details) {
                console.error('📋 Detalles:', data.details);
                errorMsg += ' - ' + data.details;
            }
            
            mensaje.className = 'mensaje error';
            mensaje.textContent = '❌ ' + errorMsg;
            mensaje.style.display = 'block';
            
            submitBtn.disabled = false;
            btnIcon.textContent = '✓';
            btnText.textContent = 'Publicar';
        }
    });
});
</script>

<?php require 'partials/footer.php'; ?>
