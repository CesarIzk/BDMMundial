
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
