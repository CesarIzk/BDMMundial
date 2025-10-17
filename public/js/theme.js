document.addEventListener("DOMContentLoaded", () => {
  const form1 = document.getElementById('login-form');

  form1.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form1);

    fetch('inicioSesion.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Insertar el mensaje en el modal
      document.getElementById('mensajeModalBody').textContent = data.message;

      // Cambiar color de encabezado según éxito o error
      const modalHeader = document.querySelector('#mensajeModal .modal-header');
      if (data.success) {
        modalHeader.classList.remove('bg-danger');
        modalHeader.classList.add('bg-success');
        document.getElementById('mensajeModalLabel').textContent = "Éxito";
      } else {
        modalHeader.classList.remove('bg-success');
        modalHeader.classList.add('bg-danger');
        document.getElementById('mensajeModalLabel').textContent = "Error";
      }

      // Mostrar modal
      const modal = new bootstrap.Modal(document.getElementById('mensajeModal'));
      modal.show();

      // Si es login exitoso, redirige tras 2s
      if (data.success) {
        setTimeout(() => {
          window.location.href = 'index.html';
        }, 2000);
      }
    })
    .catch(error => {
      console.error("Error:", error);
      document.getElementById('mensajeModalBody').textContent = "Error al conectar con el servidor.";
      document.getElementById('mensajeModalLabel').textContent = "Error";
      const modalHeader = document.querySelector('#mensajeModal .modal-header');
      modalHeader.classList.remove('bg-success');
      modalHeader.classList.add('bg-danger');

      const modal = new bootstrap.Modal(document.getElementById('mensajeModal'));
      modal.show();
    });

  });
});


const form = document.forms["registro"];
form.addEventListener("submit", validarFormulario);

function mostrarError(id, mensaje) {
  console.log("Mostrando error en:", id, mensaje);
  document.getElementById(id).textContent = mensaje;
}

function validarFormulario() {
  event.preventDefault();
  let valido = true;

  // Limpiar errores previos
  document.querySelectorAll(".error").forEach(span => span.textContent = "");


  const nomCom = form["nombreCom"].value.trim();
  const correo = form["correo"].value.trim();
  const clave = form["contrasena"].value;
  const pais = form["nacionalidad"].value;

  // Esto valida que tenga el formato: texto@dominio.extensión
  if (!correo.match(/^[^@\s]+@[^@\s]+\.[^@\s]+$/)) {
    alert("Debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo.");
    valido = false;
  }

  // -Nombre-
  if (nomCom.length < 3) {
    alert("Nombre invalido. Nisiquiera contiene 3 caracteres")
    valido = false;
  }

  // -Contraseña-
  const regexClave = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  if (!regexClave.test(clave)) {
    alert("Debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo.");
    valido = false;
  }

  // Si todo es válido, enviamos el formulario (o manejarlo con AJAX)
  if (valido) {
    alert("Se ha registrado correctamente");
    form.submit();
  }
  return valido;
}

// === Estado persistente de tema === //

const body = document.body;
const toggleBtn = document.getElementById('toggle-mode');
const icon = toggleBtn.querySelector('i');

// aplica preferencia guardada
const saved = localStorage.getItem('theme');
if (saved === 'dark') {
  body.classList.add('dark-mode');
  icon.classList.replace('fa-moon','fa-sun');
}

toggleBtn.addEventListener('click', () => {
  body.classList.toggle('dark-mode');
  const dark = body.classList.contains('dark-mode');
  icon.classList.toggle('fa-sun', dark);
  icon.classList.toggle('fa-moon', !dark);
  localStorage.setItem('theme', dark ? 'dark' : 'light');
});