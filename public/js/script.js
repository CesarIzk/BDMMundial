document.addEventListener("DOMContentLoaded", () => {
  // === LOGIN AJAX ===
  const form1 = document.getElementById('login-form');
  if (form1) {
    form1.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(form1);
      fetch('inicioSesion.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        document.getElementById('mensajeModalBody').textContent = data.message;
        const modalHeader = document.querySelector('#mensajeModal .modal-header');
        if (data.success) {
          modalHeader.classList.remove('bg-danger');
          modalHeader.classList.add('bg-success');
          document.getElementById('mensajeModalLabel').textContent = "Éxito";
          setTimeout(() => window.location.href = 'index.html', 2000);
        } else {
          modalHeader.classList.remove('bg-success');
          modalHeader.classList.add('bg-danger');
          document.getElementById('mensajeModalLabel').textContent = "Error";
        }
        new bootstrap.Modal(document.getElementById('mensajeModal')).show();
      })
      .catch(() => {
        document.getElementById('mensajeModalBody').textContent = "Error al conectar con el servidor.";
        new bootstrap.Modal(document.getElementById('mensajeModal')).show();
      });
    });
  }

  // === REGISTRO ===
  const form = document.forms["registro"];
  if (form) {
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      let valido = true;
      const nomCom = form["nombreCom"].value.trim();
      const correo = form["correo"].value.trim();
      const clave = form["contrasena"].value;

      if (!correo.match(/^[^@\s]+@[^@\s]+\.[^@\s]+$/)) {
        alert("Correo inválido");
        valido = false;
      }
      if (nomCom.length < 3) {
        alert("Nombre inválido. Debe tener al menos 3 caracteres");
        valido = false;
      }
      const regexClave = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (!regexClave.test(clave)) {
        alert("Contraseña inválida");
        valido = false;
      }

      if (valido) {
        alert("Se ha registrado correctamente");
        form.submit();
      }
    });
  }

  // === MODO OSCURO ===
  const body = document.body;
  const toggleBtn = document.getElementById('toggle-mode');
  if (toggleBtn) {
    const icon = toggleBtn.querySelector('i');
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') {
      body.classList.add('dark-mode');
      icon.classList.replace('fa-moon', 'fa-sun');
    }
    toggleBtn.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      const dark = body.classList.contains('dark-mode');
      icon.classList.toggle('fa-sun', dark);
      icon.classList.toggle('fa-moon', !dark);
      localStorage.setItem('theme', dark ? 'dark' : 'light');
    });
  }
});
