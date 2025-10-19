document.addEventListener("DOMContentLoaded", () => {
  const htmlEl = document.documentElement;
  const toggleBtn = document.getElementById("toggle-mode");
  const icon = toggleBtn.querySelector("i");

  // Configura icono al cargar
  if (htmlEl.classList.contains("dark-mode")) {
    icon.classList.replace("fa-moon", "fa-sun");
  } else {
    icon.classList.replace("fa-sun", "fa-moon");
  }

  // Toggle
  toggleBtn.addEventListener("click", () => {
    const isDark = htmlEl.classList.toggle("dark-mode");
    localStorage.setItem("theme", isDark ? "dark" : "light");
    icon.classList.toggle("fa-moon");
    icon.classList.toggle("fa-sun");
  });
});
