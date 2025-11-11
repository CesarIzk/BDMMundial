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

    // ========================================================
    // 📊 DASHBOARD PRINCIPAL
    // ========================================================
    public function dashboard()
    {
        $this->authorizeAdmin();

        $stats = [
            'totalUsuarios' => $this->userModel->count(),
            'nuevosHoy' => $this->userModel->countNuevosHoy(),
            'totalPublicaciones' => $this->publicacionModel->countAll(),
            'publicacionesHoy' => $this->publicacionModel->countNuevasHoy(),
            'usuariosActivos' => $this->userModel->countActivosRecientes(7),
            'contenidoOculto' => $this->publicacionModel->countByEstado('oculto'),
            'totalComentarios' => $this->publicacionModel->getTotalComentarios()
        ];

        $topUsers = $this->userModel->getTopUsers(5);
        $recentActivity = $this->getRecentActivity(10);
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

    // ========================================================
    // 📈 REPORTES
    // ========================================================
    public function reportes()
    {
        $this->authorizeAdmin();

        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $reportes = $this->publicacionModel->getReporteGeneralRango($fechaInicio, $fechaFin);
        $reportes['totalUsuarios'] = $this->userModel->countRango($fechaInicio, $fechaFin);
        $reportes['crecimientoUsuarios'] = 0;
        $reportes['crecimientoPublicaciones'] = 0;

        $paises = $this->userModel->getUsuariosPorPais();
        $paisesLabels = array_column($paises, 'pais');
        $paisesData = array_column($paises, 'total');

        $contenido = $this->publicacionModel->getContenidoStatsRango($fechaInicio, $fechaFin);
        $contenidoData = [
            'texto' => $contenido['texto'] ?? 0,
            'imagen' => $contenido['imagen'] ?? 0,
            'video' => $contenido['video'] ?? 0,
        ];

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

    // ========================================================
    // 👥 USUARIOS Y PUBLICACIONES
    // ========================================================
    public function users()
    {
        $this->authorizeAdmin();
        $usuarios = $this->userModel->all(100);
        return view('admin/usuarios.php', ['usuarios' => $usuarios]);
    }

    public function posts()
    {
        $this->authorizeAdmin();

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

    // ========================================================
    // 🧱 CREAR NUEVO ADMINISTRADOR
    // ========================================================
    public function createAdminView()
    {
        $this->authorizeAdmin();
        return view('admin/crear-admin.php');
    }

public function storeAdmin()
{
    $this->authorizeAdmin();

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmNewPassword = trim($_POST['confirm_new_password'] ?? '');
    $confirmAdminPassword = trim($_POST['confirm_admin_password'] ?? '');

    // 🚨 Validar campos vacíos
    if (!$nombre || !$email || !$username || !$password || !$confirmNewPassword || !$confirmAdminPassword) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        return redirect('/admin/crear');
    }

    // 🧩 Validar coincidencia de contraseña nueva
    if ($password !== $confirmNewPassword) {
        $_SESSION['error'] = "Las contraseñas del nuevo administrador no coinciden.";
        return redirect('/admin/crear');
    }

    // 🔐 Verificar contraseña del admin actual (autorización)
    $adminActual = $_SESSION['user'];
    $adminDB = $this->userModel->findById($adminActual['idUsuario']);

    if (!$adminDB || !password_verify($confirmAdminPassword, $adminDB['contrasena'])) {
        $_SESSION['error'] = "Tu contraseña no es correcta. No tienes permiso para crear un nuevo administrador.";
        return redirect('/admin/crear');
    }

    // 🚫 Verificar duplicados
    if ($this->userModel->existsByEmailOrUsername($email, $username)) {
        $_SESSION['error'] = "El correo o nombre de usuario ya está en uso.";
        return redirect('/admin/crear');
    }

    // ✅ Crear el nuevo administrador
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $this->userModel->create([
        'Nombre' => $nombre,
        'email' => $email,
        'username' => $username,
        'contrasena' => $hash,
        'rol' => 'admin',
        'estado' => 'activo'
    ]);

    $_SESSION['success'] = "Administrador creado exitosamente.";
    return redirect('/admin/usuarios');
}


    // ========================================================
    // 🔒 FUNCIONES PRIVADAS
    // ========================================================
    private function authorizeAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos.";
            header('Location: /');
            exit;
        }
    }

    private function getRecentActivity($limit = 10)
    {
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

        usort($activity, fn($a, $b) => strtotime($b['fecha']) - strtotime($a['fecha']));
        return array_slice($activity, 0, $limit);
    }

    private function getChartData7Dias()
    {
        $labels = [];
        $diasCompletos = [];

        for ($i = 6; $i >= 0; $i--) {
            $labels[] = date('M d', strtotime("-$i days"));
            $diasCompletos[date('Y-m-d', strtotime("-$i days"))] = 0;
        }

        $usuarios = $this->userModel->getNuevosUsuarios7Dias();
        $publicaciones = $this->publicacionModel->getNuevasPublicaciones7Dias();

        $dataUsuarios = $this->mergeData($diasCompletos, $usuarios);
        $dataPublicaciones = $this->mergeData($diasCompletos, $publicaciones);

        return [
            'labels' => $labels,
            'usuarios' => $dataUsuarios,
            'publicaciones' => $dataPublicaciones,
        ];
    }

    private function mergeData($diasBase, $datos)
    {
        foreach ($datos as $item) {
            $diasBase[$item['dia']] = $item['total'];
        }
        return array_values($diasBase);
    }
}
