<?php

namespace Controles\Api;

use Controles\Models\User;
use Controles\Models\Publicacion;

class AdminController
{
    protected $userModel;
    protected $publicacionModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->publicacionModel = new Publicacion();
    }

    // --- MÉTODOS NUEVOS ---

    /**
     * Mostrar el Dashboard principal
     */
    public function dashboard()
{
    $this->authorizeAdmin();

    // 1. Tarjetas de Estadísticas
    $stats = [
        'totalUsuarios' => $this->userModel->count(),
        'nuevosHoy' => $this->userModel->countNuevosHoy(),
        'totalPublicaciones' => $this->publicacionModel->countAll(),
        'publicacionesHoy' => $this->publicacionModel->countNuevasHoy(),
        'usuariosActivos' => $this->userModel->countActivosRecientes(7),
        'contenidoOculto' => $this->publicacionModel->countByEstado('oculto'),

        // ✅ Nuevo: total de comentarios (usando el campo mantenido por triggers)
        'totalComentarios' => $this->publicacionModel->getTotalComentarios()
    ];

    // 2. Top Usuarios
    $topUsers = $this->userModel->getTopUsers(5); 

    // 3. Actividad Reciente
    $recentActivity = $this->getRecentActivity(10);

    // 4. Datos del Gráfico
    $chartData = $this->getChartData7Dias();

    return view('admin/dashboard.php', [
        'stats' => $stats,
        'topUsers' => $topUsers,
        'recentActivity' => $recentActivity,
        'chartLabels' => $chartData['labels'],
        'chartUsuarios' => $chartData['usuarios'],
        'chartPublicaciones' => $chartData['publicaciones']
    ]);
}


    /**
     * Mostrar la página de Reportes
     */
    public function reportes()
    {
        $this->authorizeAdmin();

        // 1. Obtener filtros de fecha
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        // 2. Métricas Generales
        $reportes = $this->publicacionModel->getReporteGeneralRango($fechaInicio, $fechaFin);
        $reportes['totalUsuarios'] = $this->userModel->countRango($fechaInicio, $fechaFin);
        // Simplificamos crecimientos (puedes mejorarlo luego)
        $reportes['crecimientoUsuarios'] = 0; 
        $reportes['crecimientoPublicaciones'] = 0;

        // 3. Gráficos
        $paises = $this->userModel->getUsuariosPorPais();
        $paisesLabels = array_column($paises, 'pais');
        $paisesData = array_column($paises, 'total');

        $contenido = $this->publicacionModel->getContenidoStatsRango($fechaInicio, $fechaFin);
        $contenidoData = [
            'texto' => $contenido['texto'] ?? 0,
            'imagen' => $contenido['imagen'] ?? 0,
            'video' => $contenido['video'] ?? 0,
        ];

        // 4. Tablas Detalladas
        $detallesDiarios = $this->publicacionModel->getReporteDetalladoRango($fechaInicio, $fechaFin);
        $topUsuariosActivos = $this->userModel->getTopActivosRango($fechaInicio, $fechaFin, 10);
        $topPublicaciones = $this->publicacionModel->getTopPopularesRango($fechaInicio, $fechaFin, 10);


        return view('admin/reportes.php', [
            'reportes' => $reportes,
            'paisesLabels' => $paisesLabels,
            'paisesData' => $paisesData,
            'contenidoData' => array_values($contenidoData),
            'detallesDiarios' => $detallesDiarios,
            'topUsuariosActivos' => $topUsuariosActivos,
            'topPublicaciones' => $topPublicaciones
        ]);
    }


    // --- MÉTODOS CORREGIDOS (sin la barra '/') ---

    public function users()
    {
        $this->authorizeAdmin();
        $usuarios = $this->userModel->all(100); 
        // CORREGIDO: Quita la barra inicial
        return view('admin/usuarios.php', ['usuarios' => $usuarios]);
    }

  public function posts()
    {
        $this->authorizeAdmin();

        // ¡AQUÍ ESTÁ EL ARREGLO!
        // Definimos las variables que faltaban
        $page = (int)($_GET['page'] ?? 1);
        $limit = 25;
        $offset = ($page - 1) * $limit;

        $publicaciones = $this->publicacionModel->allForAdmin($limit, $offset);
        $total = $this->publicacionModel->countAll();
        $pages = ceil($total / $limit);

        return view('admin/publicaciones.php', [
            'publicaciones' => $publicaciones,
            'currentPage' => $page,
            'totalPages' => $pages,
            'total' => $total
        ]);
    }

    // ... (tus otros métodos: deactivateUser, activateUser, hidePost, showPost) ...
    // Asegúrate de que el helper is_array() esté en todos los que reciben ID:
    public function deactivateUser($params)
    {
        $this->authorizeAdmin();
        $userId = $params['id'];
        $user = $this->userModel->changeStatus($userId, 'inactivo');
        $_SESSION['success'] = "Usuario {$user['Nombre']} ha sido dado de baja";
        return redirect('/admin/usuarios');
    }

    public function activateUser($params)
    {
        $this->authorizeAdmin();
        $userId = $params['id'];
        $user = $this->userModel->changeStatus($userId, 'activo');
        $_SESSION['success'] = "Usuario {$user['Nombre']} ha sido reactivado";
        return redirect('/admin/usuarios');
    }

    public function hidePost($params)
    {
        $this->authorizeAdmin();
        $postId = $params['id'];
        $this->publicacionModel->update($postId, ['estado' => 'oculto']);
        $_SESSION['success'] = "Publicación #{$postId} ha sido ocultada";
        return redirect('/admin/publicaciones');
    }

    public function showPost($params)
    {
        $this->authorizeAdmin();
        $postId = $params['id'];
        $this->publicacionModel->update($postId, ['estado' => 'publico']);
        $_SESSION['success'] = "Publicación #{$postId} ha sido publicada";
        return redirect('/admin/publicaciones');
    }

    // --- MÉTODOS PRIVADOS HELPER ---

    private function authorizeAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos";
            header('Location: /');
            exit;
        }
    }

    /**
     * Helper para combinar actividad reciente
     */
    private function getRecentActivity($limit = 10)
    {
        // Esta es una simulación. Idealmente tendrías una tabla 'logs'
        $nuevosUsuarios = $this->userModel->getRecientes($limit);
        $nuevosPosts = $this->publicacionModel->getRecientes($limit);

        $activity = [];
        foreach ($nuevosUsuarios as $user) {
            $activity[] = [
                'username' => $user['username'],
                'action' => 'Se unió a MundialFan',
                'fecha' => $user['fechaRegistro']
            ];
        }
        foreach ($nuevosPosts as $post) {
            $activity[] = [
                'username' => $post['username'],
                'action' => 'Creó una publicación',
                'fecha' => $post['postdate']
            ];
        }

        // Ordenar por fecha descendente
        usort($activity, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        return array_slice($activity, 0, $limit);
    }

    /**
     * Helper para formatear datos del gráfico
     */
    private function getChartData7Dias()
    {
        $labels = [];
        $dataUsuarios = [];
        $dataPublicaciones = [];
        
        // Crear etiquetas para los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = date('M d', strtotime("-$i days"));
            $diasCompletos[date('Y-m-d', strtotime("-$i days"))] = 0;
        }

        // Obtener datos
        $usuarios = $this->userModel->getNuevosUsuarios7Dias();
        $publicaciones = $this->publicacionModel->getNuevasPublicaciones7Dias();
        
        // Unir datos de usuarios
        $dataTemp = $diasCompletos;
        foreach($usuarios as $item) {
            $dataTemp[$item['dia']] = $item['total'];
        }
        $dataUsuarios = array_values($dataTemp);

        // Unir datos de publicaciones
        $dataTemp = $diasCompletos;
        foreach($publicaciones as $item) {
            $dataTemp[$item['dia']] = $item['total'];
        }
        $dataPublicaciones = array_values($dataTemp);

        return [
            'labels' => $labels,
            'usuarios' => $dataUsuarios,
            'publicaciones' => $dataPublicaciones,
        ];
    }
}