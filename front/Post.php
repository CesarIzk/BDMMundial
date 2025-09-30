<?php require 'partials/header.php'; ?>
<?php require 'partials/navbar.php'; ?>

<div class="contenedor">
    <h2 class="titulo-seccion">Publicaciones Destacadas</h2>
    
    <div class="destacados-tabs">
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="tab1">Post #1</button>
            <button class="tab-button" data-tab="tab2">Post #2</button>
            <button class="tab-button" data-tab="tab3">Post #3</button>
        </div>

        <div id="tab1" class="tab-content active">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Publicación de Prueba</h3>
                    <p>Descripción:</p>
                    <p>Oigan, qué emoción lo del Mundial, la verdad no puedo esperar a que llegue el próximo año para poder verlo.</p>
                    <button type="submit"><i class="fas fa-thumbs-up"></i></button>
                    <button type="submit"><i class="fas fa-thumbs-down"></i></button>
                </div>
                <div class="form-group media-estatica">
                                      <video controls src="imagenes/Rashica.MP4"></video>
                </div>
            </div>
        </div>

        <div id="tab2" class="tab-content">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Futbolito Rapidito</h3>
                    <p>¡El futbolito es el mejor deporte de todos! ¿Están de acuerdo?</p>
                    <button type="submit"><i class="fas fa-thumbs-up"></i></button>
                    <button type="submit"><i class="fas fa-thumbs-down"></i></button>
                </div>
                <div class="form-group media-estatica">
                    <img src="imagenes/Mascota.jpg" alt="Mascota del mundial">
                </div>
            </div>
        </div>

        <div id="tab3" class="tab-content">
            <div class="destacado-post">
                <div class="form-group">
                    <h3>Metegol</h3>
                    <p>¡Metegol es mi película favorita del mundial!</p>
                    <button type="submit"><i class="fas fa-thumbs-up"></i></button>
                    <button type="submit"><i class="fas fa-thumbs-down"></i></button>
                </div>
                <div class="form-group media-estatica">
                    <img src="imagenes/Mundi.jpg" alt="Metegol">
                </div>
            </div>
        </div>
    </div>
</div>

<h2 class="titulo-seccion">Más Publicaciones</h2>

<div class="contenedor">
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
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTabId = button.dataset.tab;

                // Desactivar todas las pestañas y contenidos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Activar la pestaña y el contenido correctos
                button.classList.add('active');
                document.getElementById(targetTabId).classList.add('active');
            });
        });
    });
</script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/script.js"></script>

</body>
</html>