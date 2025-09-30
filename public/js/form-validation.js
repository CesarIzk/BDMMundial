// front/js/form-validation.js
document.addEventListener("DOMContentLoaded", () => {
    // Lógica para el formulario de inicio de sesión
    const form1 = document.getElementById('login-form');
    if (form1) { // Asegura que el formulario existe en la página
        form1.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form1);
            fetch('inicioSesion.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('mensajeModalBody').textContent = data.message;
                const modalHeader = document.querySelector('#mensajeModal .modal-header');
                if (data.success) {
                    modalHeader.classList.remove('bg-danger');
                    modalHeader.classList.add('bg-success');
                    document.getElementById('mensajeModalLabel').textContent = "Éxito";
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                } else {
                    modalHeader.classList.remove('bg-success');
                    modalHeader.classList.add('bg-danger');
                    document.getElementById('mensajeModalLabel').textContent = "Error";
                }
                const modal = new bootstrap.Modal(document.getElementById('mensajeModal'));
                modal.show();
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
    }

    // Lógica para el formulario de registro
    const form = document.forms["registro"];
    if (form) { // Asegura que el formulario existe en la página
        form.addEventListener("submit", validarFormulario);

        function validarFormulario(event) {
            event.preventDefault();
            let valido = true;
            document.querySelectorAll(".error").forEach(span => span.textContent = "");

            const nomCom = form["nombreCom"].value.trim();
            const correo = form["correo"].value.trim();
            const clave = form["contrasena"].value;

            if (!correo.match(/^[^@\s]+@[^@\s]+\.[^@\s]+$/)) {
                alert("Debe tener un formato de correo válido.");
                valido = false;
            }
            if (nomCom.length < 3) {
                alert("Nombre inválido. Debe contener al menos 3 caracteres.");
                valido = false;
            }
            const regexClave = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!regexClave.test(clave)) {
                alert("Debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo.");
                valido = false;
            }

            if (valido) {
                alert("Se ha registrado correctamente");
                form.submit();
            }
            return valido;
        }
    }
});