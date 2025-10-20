// Dark Mode Toggle Script
document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggle-mode-header");
  
  if (!toggleBtn) {
    console.error("El botón de toggle no fue encontrado");
    return;
  }

  const icon = toggleBtn.querySelector("i");
  
  // Cargar preferencia guardada del usuario
  const savedMode = localStorage.getItem("theme-mode");
  if (savedMode === "dark") {
    document.body.classList.add("dark-mode");
    if (icon) icon.classList.replace("fa-moon", "fa-sun");
  }

  // Event listener para el botón
  toggleBtn.addEventListener("click", (e) => {
    e.preventDefault();
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
      icon.classList.replace("fa-moon", "fa-sun");
      localStorage.setItem("theme-mode", "dark");
    } else {
      icon.classList.replace("fa-sun", "fa-moon");
      localStorage.setItem("theme-mode", "light");
    }
    
    console.log("Modo oscuro:", document.body.classList.contains("dark-mode"));
  });
});