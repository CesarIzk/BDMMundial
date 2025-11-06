<?php

namespace Controles\Api;

use Core\App;

class PostController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /**
     * 🔍 Listar publicaciones con búsqueda, filtro y paginación
     */
    public function index()
    {
        $categoria = $_GET['categoria'] ?? '';
        $orden = $_GET['orden'] ?? 'reciente';
        $busqueda = trim($_GET['q'] ?? '');
        $page = max((int)($_GET['page'] ?? 1), 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // Base query
        $sql = "SELECT p.*, u.Nombre, u.username, u.fotoPerfil, c.nombre AS categoriaNombre
                FROM publicaciones p
                JOIN users u ON p.idUsuario = u.idUsuario
                LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
                WHERE p.estado = 'publico'";
        $params = [];

        // 🔹 Filtro por categoría
        if (!empty($categoria)) {
            $sql .= " AND p.idCategoria = ?";
            $params[] = $categoria;
        }

        // 🔹 Filtro por búsqueda (texto, usuario, nombre o categoría)
        if (!empty($busqueda)) {
            $sql .= " AND (p.texto LIKE ? OR u.username LIKE ? OR u.Nombre LIKE ? OR c.nombre LIKE ?)";
            $params = array_merge($params, array_fill(0, 4, "%$busqueda%"));
        }

        // 🔹 Orden
        if ($orden === 'populares') {
            $sql .= " ORDER BY p.likes DESC";
        } else {
            $sql .= " ORDER BY p.postdate DESC";
        }

        $sql .= " LIMIT $limit OFFSET $offset";
        $posts = $this->db->query($sql, $params)->get();

        // 🔹 Obtener categorías
        $categorias = $this->db->query("SELECT idCategoria, nombre FROM categorias ORDER BY nombre ASC")->get();

        // 🔹 Conteo total
        $countSql = "SELECT COUNT(*) AS total
                     FROM publicaciones p
                     JOIN users u ON p.idUsuario = u.idUsuario
                     LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
                     WHERE p.estado = 'publico'";
        $countParams = [];

        if (!empty($categoria)) {
            $countSql .= " AND p.idCategoria = ?";
            $countParams[] = $categoria;
        }
        if (!empty($busqueda)) {
            $countSql .= " AND (p.texto LIKE ? OR u.username LIKE ? OR u.Nombre LIKE ? OR c.nombre LIKE ?)";
            $countParams = array_merge($countParams, array_fill(0, 4, "%$busqueda%"));
        }

        $total = $this->db->query($countSql, $countParams)->find()['total'] ?? 0;
        $pages = max(ceil($total / $limit), 1);

        // 🔹 Si es AJAX (lazy load)
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            // Solo renderizamos las tarjetas
            foreach ($posts as $post) {
                require base_path('views/partials/postCard.php');
            }
            return;
        }

        // 🔹 Renderizar vista
        return view('Post.php', [
            'publicaciones' => $posts,
            'categorias' => $categorias,
            'categoriaSeleccionada' => $categoria,
            'orden' => $orden,
            'busqueda' => $busqueda,
            'currentPage' => $page,
            'totalPages' => $pages,
            'total' => $total
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = $this->db->query("
            SELECT idCategoria, nombre FROM categorias ORDER BY nombre ASC
        ")->get();

        return view('CrearPublicacion.php', ['categorias' => $categorias]);
    }

    /**
     * Crear nueva publicación
     */
    /**
 * Crear nueva publicación
 */
public function store()
{
    // ✅ Iniciar sesión al principio
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ✅ Establecer header JSON desde el inicio
    header('Content-Type: application/json');

    // 🔐 Verificar autenticación
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        return;
    }

    // 🔐 Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        return;
    }

    $idUsuario = $_SESSION['user']['idUsuario'];

    // 🔍 Verificar estado del usuario
    $user = $this->db->query("SELECT estado FROM users WHERE idUsuario = ?", [$idUsuario])->find();

    if (!$user || $user['estado'] !== 'activo') {
        http_response_code(403);
        echo json_encode(['error' => 'Cuenta inactiva o suspendida']);
        return;
    }

    // 📝 Obtener datos del formulario
    $texto = trim($_POST['texto'] ?? '');
    $tipo = $_POST['tipo'] ?? 'texto';
    $idCategoria = $_POST['idCategoria'] ?? null;
    $archivoRuta = null;

    // ✅ Validaciones
    if (empty($texto)) {
        http_response_code(400);
        echo json_encode(['error' => 'El contenido es obligatorio']);
        return;
    }

    if (!$idCategoria) {
        http_response_code(400);
        echo json_encode(['error' => 'Debe seleccionar una categoría']);
        return;
    }

    // 📁 Manejo de archivos
    if ($tipo === 'imagen' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        $archivoRuta = $this->handleImageUpload($_FILES['imagen']);
        if ($archivoRuta === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Error al subir la imagen. Verifica el formato y tamaño (máx. 5MB)']);
            return;
        }
    } elseif ($tipo === 'video' && isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
        $archivoRuta = $this->handleVideoUpload($_FILES['video']);
        if ($archivoRuta === false) {
            http_response_code(400);
            echo json_encode(['error' => 'Error al subir el video. Verifica el formato y tamaño (máx. 50MB)']);
            return;
        }
    }

    // 💾 Insertar en base de datos
    try {
        $this->db->query(
            "INSERT INTO publicaciones (idUsuario, idCategoria, texto, tipoContenido, rutamulti, estado, postdate)
             VALUES (?, ?, ?, ?, ?, 'publico', NOW())",
            [$idUsuario, $idCategoria, htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'), $tipo, $archivoRuta]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Publicación creada exitosamente'
        ]);

    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Error al crear la publicación',
            'detalles' => $e->getMessage()
        ]);
    }
}
    /**
     * 📄 Mostrar publicación individual (para el modal)
     * @param array $params Parámetros de la ruta (ejemplo: ['id' => '7'])
     */
    public function show($params = [])
    {
        // El router siempre pasa un array con los parámetros de la URL
        $postId = $params['id'] ?? null;

        // Validar que sea un ID numérico válido
        if (!$postId || !is_numeric($postId)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID inválido o faltante']);
            return;
        }

        try {
            $post = $this->db->query("
                SELECT p.*, u.username, u.Nombre, u.fotoPerfil, c.nombre AS categoriaNombre
                FROM publicaciones p
                JOIN users u ON p.idUsuario = u.idUsuario
                LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
                WHERE p.idPublicacion = ? AND p.estado = 'publico'
            ", [$postId])->find();

            if (!$post) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Publicación no encontrada']);
                return;
            }

            // ✅ Respuesta en formato JSON
            header('Content-Type: application/json');
            echo json_encode($post, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error interno', 'detalles' => $e->getMessage()]);
        }
    }

    /**
     * ❤️ Dar o quitar "like" a una publicación (AJAX)
     */
    public function like()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 🔐 Verificar autenticación
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        $idUsuario = $_SESSION['user']['idUsuario'];
        $postId = $_POST['postId'] ?? null;

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de publicación faltante']);
            return;
        }

        try {
            // 🔍 Comprobar si ya existe el like
            $likeExistente = $this->db->query(
                "SELECT idLike FROM likes WHERE idUsuario = ? AND idPublicacion = ?",
                [$idUsuario, $postId]
            )->find();

            if ($likeExistente) {
                // ❌ Eliminar like
                $this->db->query("DELETE FROM likes WHERE idUsuario = ? AND idPublicacion = ?", [$idUsuario, $postId]);
                $this->db->query("UPDATE publicaciones SET likes = GREATEST(likes - 1, 0) WHERE idPublicacion = ?", [$postId]);
                $accion = 'unliked';
            } else {
                // ❤️ Agregar like
                $this->db->query("INSERT INTO likes (idUsuario, idPublicacion) VALUES (?, ?)", [$idUsuario, $postId]);
                $this->db->query("UPDATE publicaciones SET likes = likes + 1 WHERE idPublicacion = ?", [$postId]);
                $accion = 'liked';
            }

            // 🔢 Obtener total actualizado
            $nuevoTotal = $this->db->query(
                "SELECT likes FROM publicaciones WHERE idPublicacion = ?",
                [$postId]
            )->find()['likes'] ?? 0;

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'accion' => $accion,
                'likes' => $nuevoTotal
            ]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Error al procesar like',
                'detalles' => $e->getMessage()
            ]);
        }
    }

    // === FUNCIONES PRIVADAS ===
 /**
 * 🖼️ Manejar subida de imágenes de publicaciones
 */
private function handleImageUpload($file)
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;

    // Validaciones básicas
    if ($file['size'] > 5 * 1024 * 1024) return false;
    $mime = mime_content_type($file['tmp_name']);
    $extensionesPermitidas = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];
    if (!isset($extensionesPermitidas[$mime])) return false;

    // 📁 Crear carpeta si no existe
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/posts/imagenes/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 🔤 Nombre seguro y único
    $extension = $extensionesPermitidas[$mime];
    $filename = 'post_' . time() . '_' . uniqid() . '.' . $extension;
    $rutaCompleta = $uploadDir . $filename;
    $rutaRelativa = '/uploads/posts/imagenes/' . $filename;

    // 📦 Mover el archivo
    return move_uploaded_file($file['tmp_name'], $rutaCompleta) ? $rutaRelativa : false;
}

/**
 * 🎥 Manejar subida de videos de publicaciones
 */
private function handleVideoUpload($file)
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;

    if ($file['size'] > 50 * 1024 * 1024) return false;
    $mime = mime_content_type($file['tmp_name']);
    $extensionesPermitidas = [
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
        'video/x-msvideo' => 'avi'
    ];
    if (!isset($extensionesPermitidas[$mime])) return false;

    // 📁 Crear carpeta si no existe
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/posts/videos/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 🔤 Nombre seguro
    $extension = $extensionesPermitidas[$mime];
    $filename = 'post_' . time() . '_' . uniqid() . '.' . $extension;
    $rutaCompleta = $uploadDir . $filename;
    $rutaRelativa = '/uploads/posts/videos/' . $filename;

    return move_uploaded_file($file['tmp_name'], $rutaCompleta) ? $rutaRelativa : false;
}

}