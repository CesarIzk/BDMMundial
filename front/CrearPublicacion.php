<?php 

require 'partials/header.php'; 
?>

<div class="contenedor">
    <h2 class="titulo-seccion">Crear Nueva Publicación</h2>
    
    <form id="formPublicacion" enctype="multipart/form-data" class="form-publicacion">
        <div class="form-group">
            <label for="texto">¿Qué estás pensando?</label>
            <textarea 
                id="texto" 
                name="texto" 
                rows="4" 
                maxlength="500" 
                placeholder="Comparte algo sobre el Mundial..."
                required
            ></textarea>
            <small class="contador-caracteres">0/500 caracteres</small>
        </div>

        <div class="form-group">
            <label>Tipo de contenido:</label>
            <div class="tipo-contenido">
                <label>
                    <input type="radio" name="tipo" value="texto" checked> Solo texto
                </label>
                <label>
                    <input type="radio" name="tipo" value="imagen"> Con imagen
                </label>
                <label>
                    <input type="radio" name="tipo" value="video"> Con video
                </label>
            </div>
        </div>

        <div class="form-group archivo-upload" id="imagenUpload" style="display:none;">
            <label for="imagen">Subir Imagen (máx. 5MB)</label>
            <input 
                type="file" 
                id="imagen" 
                name="imagen" 
                accept="image/jpeg,image/png,image/gif"
            >
            <div class="preview" id="imagenPreview"></div>
        </div>

        <div class="form-group archivo-upload" id="videoUpload" style="display:none;">
            <label for="video">Subir Video (máx. 50MB)</label>
            <input 
                type="file" 
                id="video" 
                name="video" 
                accept="video/mp4,video/quicktime,video/x-msvideo"
            >
            <div class="preview" id="videoPreview"></div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn-publicar">Publicar</button>
            <a href="/Post" class="btn-cancelar">Cancelar</a>
        </div>

        <div id="mensaje" class="mensaje" style="display:none;"></div>
    </form>
</div>

<style>
.form-publicacion {
    max-width: 600px;
    margin: 2rem auto;
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
}

.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
}

.contador-caracteres {
    display: block;
    margin-top: 0.25rem;
    color: #666;
    font-size: 0.875rem;
}

.tipo-contenido {
    display: flex;
    gap: 1.5rem;
}

.tipo-contenido label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: normal;
}

.archivo-upload input[type="file"] {
    width: 100%;
    padding: 0.5rem;
    border: 2px dashed #ddd;
    border-radius: 4px;
    cursor: pointer;
}

.preview {
    margin-top: 1rem;
}

.preview img, .preview video {
    max-width: 100%;
    max-height: 300px;
    border-radius: 4px;
}

.btn-publicar {
    background: #007bff;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-publicar:hover {
    background: #0056b3;
}

.btn-publicar:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.btn-cancelar {
    display: inline-block;
    margin-left: 1rem;
    padding: 0.75rem 2rem;
    color: #666;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: all 0.3s;
}

.btn-cancelar:hover {
    background: #f5f5f5;
}

.mensaje {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 4px;
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
        } else {
            contador.style.color = '#666';
        }
    });

    // Mostrar/ocultar campos según tipo
    tipoRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            imagenUpload.style.display = 'none';
            videoUpload.style.display = 'none';
            imagenInput.required = false;
            videoInput.required = false;

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
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
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
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Publicando...';
        mensaje.style.display = 'none';

        try {
            const response = await fetch('/Post/store', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                mensaje.className = 'mensaje exito';
                mensaje.textContent = '¡Publicación creada exitosamente!';
                mensaje.style.display = 'block';
                
                setTimeout(() => {
                    window.location.href = '/Post';
                }, 1500);
            } else {
                throw new Error(data.error || 'Error al crear la publicación');
            }
        } catch (error) {
            mensaje.className = 'mensaje error';
            mensaje.textContent = error.message;
            mensaje.style.display = 'block';
            
            submitBtn.disabled = false;
            submitBtn.textContent = 'Publicar';
        }
    });
});
</script>

<?php require 'partials/footer.php'; ?>