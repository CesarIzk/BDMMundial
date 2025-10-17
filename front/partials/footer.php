 <section class="formularios">
    <div class="contenedor">
      <h2 class="titulo-seccion">Únete a la Comunidad</h2>
      <div class="formularios-contenedor">
        <div class="formulario">
          <h3><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h3>
          <form id="login-form" name="inicioDeSesion" action="inicioSesion.php" method="POST">
            <input type="email" id="correo" name="correo" placeholder="Correo electrónico" required>
            <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
          </form>
        </div>

        <div class="formulario">
          <h3><i class="fas fa-user-plus"></i> Registrarse</h3>
          <form id="register-form" name="registro" action="registro.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nombreCom"  placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <input type="password" name="contrasena2" placeholder="Confirmar contraseña" required>
            <select name="nacionalidad" placeholder="Nacionalidad (Pais)" required>
                <option value="pais">Mexico</option>
                </select>
            <button type="submit">Crear Cuenta</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="contenedor">
      <div class="footer-contenido">
        <div class="footer-seccion">
          <h4>MundialFan</h4>
          <p>Tu destino para todo lo relacionado con el Mundial de Fútbol.</p>
        </div>
        <div class="footer-seccion">
          <h4>Enlaces Rápidos</h4>
          <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Calendario de Partidos</a></li>
            <li><a href="#">Resultados</a></li>
          </ul>
        </div>
        <div class="footer-seccion">
          <h4>Contacto</h4>
          <p><i class="fas fa-map-marker-alt"></i> Av. del Fútbol 123</p>
          <p><i class="fas fa-phone"></i> +1 234 567 8900</p>
          <p><i class="fas fa-envelope"></i> info@mundialfan.com</p>
        </div>
        <div class="footer-seccion">
          <h4>Síguenos</h4>
          <div class="redes-sociales">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
      <div class="copyright">
        <p>&copy; 2023 MundialFan. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="mensajeModalBody">
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/script.js"></script>

</body>
</html>