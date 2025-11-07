// Control del menú hamburguesa en móvil
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.querySelector(".menu-toggle");
  const navbar = document.getElementById("navbar-menu");

  if (!menuToggle || !navbar) return;

  // Abre/cierra el menú
menuToggle.addEventListener("click", () => {
  navbar.classList.toggle("active");
  menuToggle.classList.toggle("active");
  const icon = menuToggle.querySelector("i");
  icon.classList.toggle("fa-bars");
  icon.classList.toggle("fa-xmark");
});


  // Cierra el menú al hacer clic en un enlace
  const navLinks = navbar.querySelectorAll("a");
  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      navbar.classList.remove("active");
      menuToggle.classList.remove("active");
    });
  });

  // Cierra el menú al hacer clic fuera
  document.addEventListener("click", (e) => {
    if (!e.target.closest("nav")) {
      navbar.classList.remove("active");
      menuToggle.classList.remove("active");
    }
  });
});