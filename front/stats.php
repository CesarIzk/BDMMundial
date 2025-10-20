<?php require 'partials/header.php'; ?>

<div class="contenedor contenido-principal">
    <h2 class="titulo-seccion">Estadísticas del Mundial</h2>
    
    <!-- Filtros -->
    <div class="stats-filters">
        <select class="form-select" id="filterYear">
            <option value="">Todos los años</option>
            <option value="2022">2022</option>
            <option value="2026">2026</option>
        </select>
        <select class="form-select" id="filterTournament">
            <option value="">Todos los campeonatos</option>
            <option value="mundial">Mundial</option>
            <option value="clasificatorias">Clasificatorias</option>
        </select>
    </div>

    <!-- Grid de tarjetas de estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-futbol"></i>
            </div>
            <div class="stat-content">
                <h3>2,847</h3>
                <p>Goles Totales</p>
                <span class="stat-change positive">↑ 12% vs mes anterior</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>32</h3>
                <p>Equipos Participantes</p>
                <span class="stat-change">En el último torneo</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <h3>5</h3>
                <p>Campeones Distintos</p>
                <span class="stat-change positive">En los últimos 20 años</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users-crown"></i>
            </div>
            <div class="stat-content">
                <h3>134</h3>
                <p>Partidos Jugados</p>
                <span class="stat-change">En campeonatos oficiales</span>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="stats-charts">
        <div class="chart-container">
            <h3>Goles por Equipo (Top 10)</h3>
            <div class="chart-placeholder" id="chart-goles">
                <canvas id="chartGoles"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <h3>Distribución de Victorias</h3>
            <div class="chart-placeholder" id="chart-victorias">
                <canvas id="chartVictorias"></canvas>
            </div>
        </div>

        <div class="chart-container full-width">
            <h3>Evolución de Goles por Torneo</h3>
            <div class="chart-placeholder" id="chart-evolucion">
                <canvas id="chartEvolucion"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <h3>Promedio de Goles por Partido</h3>
            <div class="chart-placeholder" id="chart-promedio">
                <canvas id="chartPromedio"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="stats-table-container">
        <h3>Resultados Recientes</h3>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Equipo Local</th>
                    <th>Resultado</th>
                    <th>Equipo Visitante</th>
                    <th>Campeonato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>15/11/2024</td>
                    <td>Argentina</td>
                    <td class="resultado">3 - 1</td>
                    <td>Brasil</td>
                    <td><span class="badge badge-primary">Clasificatorias</span></td>
                </tr>
                <tr>
                    <td>14/11/2024</td>
                    <td>Francia</td>
                    <td class="resultado">2 - 2</td>
                    <td>Alemania</td>
                    <td><span class="badge badge-primary">Amistoso</span></td>
                </tr>
                <tr>
                    <td>13/11/2024</td>
                    <td>España</td>
                    <td class="resultado">4 - 0</td>
                    <td>Portugal</td>
                    <td><span class="badge badge-success">Liga de Naciones</span></td>
                </tr>
                <tr>
                    <td>12/11/2024</td>
                    <td>Uruguay</td>
                    <td class="resultado">1 - 1</td>
                    <td>Paraguay</td>
                    <td><span class="badge badge-primary">Clasificatorias</span></td>
                </tr>
                <tr>
                    <td>11/11/2024</td>
                    <td>México</td>
                    <td class="resultado">0 - 2</td>
                    <td>Colombia</td>
                    <td><span class="badge badge-primary">Clasificatorias</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Estadísticas por Jugador -->
    <div class="player-stats">
        <h3>Top Goleadores</h3>
        <div class="player-list">
            <div class="player-card">
                <div class="player-rank">1</div>
                <div class="player-info">
                    <h4>Kylian Mbappé</h4>
                    <p>Francia</p>
                </div>
                <div class="player-stats-info">
                    <span class="stat-badge">47 Goles</span>
                    <span class="stat-badge">15 Asistencias</span>
                </div>
            </div>

            <div class="player-card">
                <div class="player-rank">2</div>
                <div class="player-info">
                    <h4>Lionel Messi</h4>
                    <p>Argentina</p>
                </div>
                <div class="player-stats-info">
                    <span class="stat-badge">45 Goles</span>
                    <span class="stat-badge">22 Asistencias</span>
                </div>
            </div>

            <div class="player-card">
                <div class="player-rank">3</div>
                <div class="player-info">
                    <h4>Cristiano Ronaldo</h4>
                    <p>Portugal</p>
                </div>
                <div class="player-stats-info">
                    <span class="stat-badge">41 Goles</span>
                    <span class="stat-badge">18 Asistencias</span>
                </div>
            </div>

            <div class="player-card">
                <div class="player-rank">4</div>
                <div class="player-info">
                    <h4>Gerd Müller</h4>
                    <p>Alemania</p>
                </div>
                <div class="player-stats-info">
                    <span class="stat-badge">39 Goles</span>
                    <span class="stat-badge">12 Asistencias</span>
                </div>
            </div>

            <div class="player-card">
                <div class="player-rank">5</div>
                <div class="player-info">
                    <h4>Pelé</h4>
                    <p>Brasil</p>
                </div>
                <div class="player-stats-info">
                    <span class="stat-badge">37 Goles</span>
                    <span class="stat-badge">10 Asistencias</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/stats.js"></script>

<?php require 'partials/footer.php'; ?>