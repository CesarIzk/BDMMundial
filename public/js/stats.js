// js/stats.js - Gráficos de Estadísticas con Chart.js

document.addEventListener("DOMContentLoaded", () => {
  const isDarkMode = document.body.classList.contains("dark-mode");
  
  // Colores según modo
  const textColor = isDarkMode ? "#e0e0e0" : "#333333";
  const gridColor = isDarkMode ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.1)";
  const primaryColor = "#b00000";
  const secondaryColor = "#667eea";

  // ===== GRÁFICO 1: GOLES POR EQUIPO =====
  const ctxGoles = document.getElementById("chartGoles");
  if (ctxGoles) {
    new Chart(ctxGoles.getContext("2d"), {
      type: "bar",
      data: {
        labels: ["Argentina", "Francia", "Brasil", "Alemania", "España", "Italia", "Holanda", "Portugal", "Uruguay", "México"],
        datasets: [
          {
            label: "Goles",
            data: [156, 142, 138, 124, 118, 112, 108, 102, 95, 88],
            backgroundColor: primaryColor,
            borderColor: primaryColor,
            borderWidth: 0,
            borderRadius: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: { color: textColor },
          },
          x: {
            grid: { display: false },
            ticks: { color: textColor },
          },
        },
      },
    });
  }

  // ===== GRÁFICO 2: DISTRIBUCIÓN DE VICTORIAS (PIE) =====
  const ctxVictorias = document.getElementById("chartVictorias");
  if (ctxVictorias) {
    new Chart(ctxVictorias.getContext("2d"), {
      type: "doughnut",
      data: {
        labels: ["Victorias", "Empates", "Derrotas"],
        datasets: [
          {
            data: [45, 28, 27],
            backgroundColor: [primaryColor, secondaryColor, "#e0e0e0"],
            borderColor: isDarkMode ? "#1a1a1a" : "#ffffff",
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            labels: {
              color: textColor,
              font: { size: 12 },
            },
          },
        },
      },
    });
  }

  // ===== GRÁFICO 3: EVOLUCIÓN DE GOLES POR TORNEO (LÍNEA) =====
  const ctxEvolucion = document.getElementById("chartEvolucion");
  if (ctxEvolucion) {
    new Chart(ctxEvolucion.getContext("2d"), {
      type: "line",
      data: {
        labels: ["2006", "2010", "2014", "2018", "2022", "2026"],
        datasets: [
          {
            label: "Goles Totales",
            data: [147, 145, 171, 169, 172, 185],
            borderColor: primaryColor,
            backgroundColor: "rgba(176, 0, 0, 0.1)",
            fill: true,
            tension: 0.4,
            pointBackgroundColor: primaryColor,
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 5,
            borderWidth: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            labels: { color: textColor },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: { color: textColor },
          },
          x: {
            grid: { color: gridColor },
            ticks: { color: textColor },
          },
        },
      },
    });
  }

  // ===== GRÁFICO 4: PROMEDIO DE GOLES POR PARTIDO =====
  const ctxPromedio = document.getElementById("chartPromedio");
  if (ctxPromedio) {
    new Chart(ctxPromedio.getContext("2d"), {
      type: "radar",
      data: {
        labels: ["Ataque", "Defensa", "Control", "Velocidad", "Precisión"],
        datasets: [
          {
            label: "Promedio",
            data: [85, 72, 88, 79, 91],
            borderColor: primaryColor,
            backgroundColor: "rgba(176, 0, 0, 0.15)",
            pointBackgroundColor: primaryColor,
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            labels: { color: textColor },
          },
        },
        scales: {
          r: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: {
              color: textColor,
              backdropColor: "transparent",
            },
          },
        },
      },
    });
  }

  // ===== FILTROS =====
  const filterYear = document.getElementById("filterYear");
  const filterTournament = document.getElementById("filterTournament");

  if (filterYear) {
    filterYear.addEventListener("change", (e) => {
      console.log("Año seleccionado:", e.target.value);
    });
  }

  if (filterTournament) {
    filterTournament.addEventListener("change", (e) => {
      console.log("Campeonato seleccionado:", e.target.value);
    });
  }
});