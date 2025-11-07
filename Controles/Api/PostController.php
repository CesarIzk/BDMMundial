<?php

namespace Controles\Api;


use Core\App;
use Cloudinary\Cloudinary;

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

        if (!empty($categoria)) {
            $sql .= " AND p.idCategoria = ?";
            $params[] = $categoria;
        }

        if (!empty($busqueda)) {
            $sql .= " AND (p.texto LIKE ? OR u.username LIKE ? OR u.Nombre LIKE ? OR c.nombre LIKE ?)";
            $params = array_merge($params, array_fill(0, 4, "%$busqueda%"));
        }

        if ($orden === 'populares') {
            $sql .= " ORDER BY p.likes DESC";
        } else {
            $sql .= " ORDER BY p.postdate DESC";
        }

        $sql .= " LIMIT $limit OFFSET $offset";
        $posts = $this->db->query($sql, $params)->get();

        $categorias = $this->db->query("SELECT idCategoria, nombre FROM categorias ORDER BY nombre ASC")->get();

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

        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            foreach ($posts as $post) {
                require base_path('views/partials/publicCard.php');
            }
            return;
        }

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
    public function store()
{
    error_log('🌩️ ENV VARS => ' . json_encode([
    'CLOUDINARY_NAME' => $_ENV['CLOUDINARY_NAME'] ?? null,
    'CLOUDINARY_KEY' => $_ENV['CLOUDINARY_KEY'] ?? null,
    'CLOUDINARY_APISECRET' => $_ENV['CLOUDINARY_APISECRET'] ?? null
]));

    // 🔒 Asegurar tipo de respuesta JSON desde el inicio
    header_remove("X-Powered-By");
    header('Content-Type: application/json; charset=utf-8');

    try {
        // 🧠 Verificar sesión
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['idUsuario'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado', 'message' => 'Debes iniciar sesión']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }

        $idUsuario = (int)$_SESSION['user']['idUsuario'];

        // ⚙️ Verificar usuario activo
        $user = $this->db->query("SELECT estado FROM users WHERE idUsuario = ?", [$idUsuario])->find();
        if (!$user) {
            throw new \Exception("Usuario no encontrado en BD");
        }
        if ($user['estado'] !== 'activo') {
            http_response_code(403);
            echo json_encode(['error' => 'Cuenta inactiva']);
            exit;
        }

        // 📝 Obtener datos del formulario
        $texto = trim($_POST['texto'] ?? '');
        $tipo = $_POST['tipo'] ?? 'texto';
        $idCategoria = $_POST['idCategoria'] ?? null;

        // ⚠️ Validaciones
        if (empty($texto)) {
            http_response_code(400);
            echo json_encode(['error' => 'El contenido es obligatorio']);
            exit;
        }
        if (strlen($texto) > 500) {
            http_response_code(400);
            echo json_encode(['error' => 'Máximo 500 caracteres']);
            exit;
        }
        if (!$idCategoria || !is_numeric($idCategoria)) {
            http_response_code(400);
            echo json_encode(['error' => 'Categoría inválida']);
            exit;
        }

        // 📁 Manejo de archivos multimedia
        $archivoRuta = null;

        if ($tipo === 'imagen' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            $archivoRuta = $this->handleImageUpload($_FILES['imagen']);
            if ($archivoRuta === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Error al subir imagen']);
                exit;
            }
        } elseif ($tipo === 'video' && isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
            $archivoRuta = $this->handleVideoUpload($_FILES['video']);
            if ($archivoRuta === false) {
                http_response_code(400);
                echo json_encode(['error' => 'Error al subir video']);
                exit;
            }
        }

        // 💾 Guardar en BD
        $query = "INSERT INTO publicaciones (idUsuario, idCategoria, texto, tipoContenido, rutamulti, estado, postdate)
                  VALUES (?, ?, ?, ?, ?, 'publico', NOW())";

        $params = [
            $idUsuario,
            (int)$idCategoria,
            htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'),
            $tipo,
            $archivoRuta
        ];

        $this->db->query($query, $params);

        // ✅ Respuesta exitosa
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Publicación creada exitosamente',
            'media_url' => $archivoRuta
        ]);
        exit;

    } catch (\PDOException $e) {
        error_log("❌ PDO Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Error de base de datos',
            'message' => $e->getMessage()
        ]);
        exit;
    } catch (\Exception $e) {
        error_log("❌ Error general: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => 'Error al crear publicación',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

    /**
     * 📄 Mostrar publicación individual
     */
    public function show($params = [])
    {
        $postId = $params['id'] ?? null;

        if (!$postId || !is_numeric($postId)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID inválido']);
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

            header('Content-Type: application/json');
            echo json_encode($post, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error interno', 'detalles' => $e->getMessage()]);
        }
    }

    /**
     * ❤️ Like/Unlike
     */
    public function like()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

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
            $likeExistente = $this->db->query(
                "SELECT idLike FROM likes WHERE idUsuario = ? AND idPublicacion = ?",
                [$idUsuario, $postId]
            )->find();

            if ($likeExistente) {
                $this->db->query("DELETE FROM likes WHERE idUsuario = ? AND idPublicacion = ?", [$idUsuario, $postId]);
                $this->db->query("UPDATE publicaciones SET likes = GREATEST(likes - 1, 0) WHERE idPublicacion = ?", [$postId]);
                $accion = 'unliked';
            } else {
                $this->db->query("INSERT INTO likes (idUsuario, idPublicacion) VALUES (?, ?)", [$idUsuario, $postId]);
                $this->db->query("UPDATE publicaciones SET likes = likes + 1 WHERE idPublicacion = ?", [$postId]);
                $accion = 'liked';
            }

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



private function getCloudinaryInstance()
{
    $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? $_ENV['CLOUDINARY_NAME'] ?? '';
    $apiKey    = $_ENV['CLOUDINARY_API_KEY'] ?? $_ENV['CLOUDINARY_KEY'] ?? '';
    $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? $_ENV['CLOUDINARY_APISECRET'] ?? '';

    if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
        error_log("⚠️ Cloudinary no configurado correctamente: " . json_encode([
            'name' => $cloudName,
            'key' => $apiKey,
            'secret' => $apiSecret
        ]));
        throw new \Exception("Cloudinary credentials missing");
    }

    return new Cloudinary([
        'cloud' => [
            'cloud_name' => $cloudName,
            'api_key'    => $apiKey,
            'api_secret' => $apiSecret,
        ]
    ]);
}



private function isRailway()
{
    // Railway define esta variable automáticamente
    return isset($_ENV['RAILWAY_ENVIRONMENT']);
}


/**
 * 🖼️ Subir imagen (local o Cloudinary)
 */
private function handleImageUpload($file)
{
    try {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
        if ($file['error'] !== UPLOAD_ERR_OK) return false;

        // Si estamos en Railway -> Cloudinary
        if ($this->isRailway()) {
            $cloudinary = $this->getCloudinaryInstance();
            $upload = $cloudinary->uploadApi()->upload($file['tmp_name'], [
                'folder' => 'mundialfan/imagenes'
            ]);
            return $upload['secure_url'];
        }

        // Local (desarrollo)
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/publics/imagenes/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'post_' . time() . '_' . uniqid() . '.' . $ext;
        $rutaRelativa = '/uploads/publics/imagenes/' . $filename;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        return $rutaRelativa;
    } catch (\Exception $e) {
        error_log("Cloudinary upload error: " . $e->getMessage());
        return false;
    }
}

/**
 * 🎥 Subir video (local o Cloudinary)
 */
private function handleVideoUpload($file)
{
    try {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) return null;
        if ($file['error'] !== UPLOAD_ERR_OK) return false;

        if ($this->isRailway()) {
            $cloudinary = $this->getCloudinaryInstance();
            $upload = $cloudinary->uploadApi()->upload($file['tmp_name'], [
                'resource_type' => 'video',
                'folder' => 'mundialfan/videos'
            ]);
            return $upload['secure_url'];
        }

        // Local
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/publics/videos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'post_' . time() . '_' . uniqid() . '.' . $ext;
        $rutaRelativa = '/uploads/publics/videos/' . $filename;
        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        return $rutaRelativa;
    } catch (\Exception $e) {
        error_log("Cloudinary video upload error: " . $e->getMessage());
        return false;
    }
}

}