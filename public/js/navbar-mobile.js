// Control de menú hamburguesa para público y admin
document.addEventListener("DOMContentLoaded", () => {
  // ======== HEADER PÚBLICO ========
  const publicToggle = document.querySelector(".menu-toggle:not(.admin-toggle)");
  const publicNavbar = document.getElementById("navbar-menu");

  if (publicToggle && publicNavbar) {
    publicToggle.addEventListener("click", () => {
      publicNavbar.classList.toggle("active");
      publicToggle.classList.toggle("active");
      const icon = publicToggle.querySelector("i");
      icon.classList.toggle("fa-bars");
      icon.classList.toggle("fa-xmark");
    });

    // Cerrar menú al hacer click fuera
    document.addEventListener("click", (e) => {
      if (!e.target.closest("nav")) {
        publicNavbar.classList.remove("active");
        publicToggle.classList.remove("active");
      }
    });
  }

  // ======== HEADER ADMIN ========
  const adminToggle = document.querySelector(".admin-toggle");
  const adminNavbar = document.getElementById("admin-navbar-menu");

  if (adminToggle && adminNavbar) {
    adminToggle.addEventListener("click", () => {
      adminNavbar.classList.toggle("active");
      adminToggle.classList.toggle("active");
      const icon = adminToggle.querySelector("i");
      icon.classList.toggle("fa-bars");
      icon.classList.toggle("fa-xmark");
    });

    // Cerrar menú al hacer click fuera
    document.addEventListener("click", (e) => {
      if (!e.target.closest("nav")) {
        adminNavbar.classList.remove("active");
        adminToggle.classList.remove("active");
      }
    });
  }
});
