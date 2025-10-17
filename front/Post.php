<?php require 'partials/header.php'; ?>
<?php require 'partials/navbar.php'; ?>

<div class="contenedor contenido-principal">
    <h2 class="titulo-seccion">Publicaciones Destacadas</h2>

    <div class="slider-container">
        <div class="slide active">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Publicación de Prueba</h3>
                    <p>Descripción:</p>
                    <p>Oigan, qué emoción lo del Mundial, la verdad no puedo esperar a que llegue el próximo año para poder verlo.</p>
                    
                    <button type="button" class="btn-accion">
                        <img src="imagenes/like.png" alt="Like">
                    </button>
                    <button type="button" class="btn-accion">
                        <img src="imagenes/dislike.png" alt="Dislike">
                    </button>

                </div>
                <div class="form-group media-estatica">
                    <video controls src="imagenes/Rashica.mp4"></video>
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Futbolito Rapidito</h3>
                    <p>¡El futbolito es el mejor deporte de todos! ¿Están de acuerdo?</p>

                    <button type="button" class="btn-accion">
                        <img src="imagenes/like.png" alt="Like">
                    </button>
                    <button type="button" class="btn-accion">
                        <img src="imagenes/dislike.png" alt="Dislike">
                    </button>
                    
                </div>
                <div class="form-group media-estatica">
                    <img src="imagenes/Mascota.jpg" alt="Mascota del mundial">
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Metegol</h3>
                    <p>¡Metegol es mi película favorita del mundial!</p>

                    <button type="button" class="btn-accion">
                        <img src="imagenes/like.png" alt="Like">
                    </button>
                    <button type="button" class="btn-accion">
                        <img src="imagenes/dislike.png" alt="Dislike">
                    </button>
                    
                </div>
                <div class="form-group media-estatica">
                    <img src="imagenes/Mundi.jpg" alt="Metegol">
                </div>
            </div>
        </div>
    </div>

    <div class="slider-nav">
        <button class="dot active" data-slide="0"></button>
        <button class="dot" data-slide="1"></button>
        <button class="dot" data-slide="2"></button>
    </div>
</div>
<div class="contenedor">
    <h2 class="titulo-seccion">Más Publicaciones</h2>
    <div class="otros-posts">
        <div class="producto">
            <div class="form-group">
                <h3>Publicación #4</h3>
                <div class="media-containerS">
                    <img src="imagenes/balon.png" alt="Balón de fútbol 2">
                </div>
            </div>
        </div>

        <div class="producto">
            <div class="form-group">
                <h3>Publicación #5</h3>
                <div class="media-containerS">
                    <img src="imagenes/Mascota.jpg" alt="Mascota del mundial 2">
                </div>
            </div>
        </div>

        <div class="producto">
            <div class="form-group">
                <h3>Publicación #6</h3>
                <div class="media-containerS">
                    <img src="imagenes/Mundi.jpg" alt="Metegol">
                </div>
            </div>
        </div>
    </div>
</div>


<script>
 document.addEventListener("DOMContentLoaded", () => {
    // Seleccionamos todos los slides y los puntos de navegación
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    // Función para mostrar un slide específico
    function showSlide(slideIndex) {
        // Ocultamos todos los slides y quitamos la clase 'active' de los puntos
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Mostramos el slide correcto y marcamos su punto como activo
        slides[slideIndex].classList.add('active');
        dots[slideIndex].classList.add('active');
    }

    // Añadimos un evento de clic a cada punto
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            // Obtenemos el índice del slide desde el atributo 'data-slide'
            const slideIndex = parseInt(dot.dataset.slide);
            showSlide(slideIndex);
        });
    });

    // Opcional: Iniciar en el primer slide por defecto
    showSlide(0);
});
</script>

<?php require 'partials/footer.php'; ?>