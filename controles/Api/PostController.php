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
     * Obtener todas las publicaciones o las de un usuario específico
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $userId = $_GET['user'] ?? null;

        if ($userId) {
            // Publicaciones de un usuario específico
       // Publicaciones de un usuario específico (línea ~28)
$posts = $this->db->query(
    "SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
     FROM publicaciones p
     JOIN users u ON p.idUsuario = u.idUsuario 
     WHERE p.idUsuario = ? AND p.estado = 'publico'
     ORDER BY p.postdate DESC 
     LIMIT {$limit} OFFSET {$offset}",  // 👈 Sin placeholders
    [$userId]
)->get();

            $total = $this->db->query(
                "SELECT COUNT(*) as count FROM publicaciones WHERE idUsuario = ? AND estado = 'publico'",
                [$userId]
            )->find()['count'];
        } else {
            // Todas las publicaciones públicas
          // Todas las publicaciones públicas (línea ~42)
$posts = $this->db->query(
    "SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
     FROM publicaciones p
     JOIN users u ON p.idUsuario = u.idUsuario 
     WHERE p.estado = 'publico'
     ORDER BY p.postdate DESC 
     LIMIT {$limit} OFFSET {$offset}",  // 👈 Sin placeholders
    []
)->get();

            $total = $this->db->query(
                "SELECT COUNT(*) as count FROM publicaciones WHERE estado = 'publico'"
            )->find()['count'];
        }

        $pages = ceil($total / $limit);

        if ($this->isJson()) {
            echo json_encode([
                'posts' => $posts,
                'currentPage' => $page,
                'totalPages' => $pages,
                'total' => $total
            ]);
        } else {
            return view('Post.php', [
                'publicaciones' => $posts,
                'currentPage' => $page,
                'totalPages' => $pages,
                'total' => $total
            ]);
        }
    }

    /**
     * Crear nueva publicación
     */
    public function store()
    {
        session_start();

        // Verificar autenticación
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

        $texto = trim($_POST['texto'] ?? $_POST['contenido'] ?? '');
        $tipo = $_POST['tipo'] ?? null;
        $idUsuario = $_SESSION['user']['idUsuario'];
        $archivoRuta = null;

        // Validar contenido
        if (empty($texto)) {
            http_response_code(400);
            echo json_encode(['error' => 'El contenido es obligatorio']);
            return;
        }

        if (strlen($texto) > 500) {
            http_response_code(400);
            echo json_encode(['error' => 'El texto no puede exceder 500 caracteres']);
            return;
        }

        // Procesar archivo de imagen
        if ($tipo === 'imagen' && isset($_FILES['imagen'])) {
            $archivoRuta = $this->handleImageUpload($_FILES['imagen']);
            if (!$archivoRuta && $_FILES['imagen']['error'] !== 4) {
                return; // Error ya manejado en handleImageUpload
            }
        }
        // Procesar archivo de video
        elseif ($tipo === 'video' && isset($_FILES['video'])) {
            $archivoRuta = $this->handleVideoUpload($_FILES['video']);
            if (!$archivoRuta && $_FILES['video']['error'] !== 4) {
                return; // Error ya manejado en handleVideoUpload
            }
        }

        try {
            // Insertar publicación
            $this->db->query(
                "INSERT INTO publicaciones (idUsuario, texto, tipoContenido, rutamulti, estado, postdate) 
                 VALUES (?, ?, ?, ?, ?, NOW())",
                [
                    $idUsuario,
                    htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'),
                    $tipo,
                    $archivoRuta,
                    'publico'
                ]
            );

            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Publicación creada exitosamente']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear la publicación']);
        }
    }

    /**
     * Obtener publicación por ID
     */
    public function show($id)
    {
        $post = $this->db->query(
            "SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario 
             WHERE p.idPublicacion = ? AND p.estado = 'publico'",
            [$id]
        )->find();

        if (!$post) {
            http_response_code(404);
            echo json_encode(['error' => 'Publicación no encontrada']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($post);
    }

    /**
     * Agregar like (AJAX)
     */
    public function like($id)
    {
        session_start();

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

        try {
            // Verificar si ya le dio like
            $existeLike = $this->db->query(
                "SELECT idLike FROM likes WHERE idUsuario = ? AND idPublicacion = ?",
                [$_SESSION['user']['idUsuario'], $id]
            )->find();

            if ($existeLike) {
                // Eliminar like
                $this->db->query(
                    "DELETE FROM likes WHERE idUsuario = ? AND idPublicacion = ?",
                    [$_SESSION['user']['idUsuario'], $id]
                );
                
                $this->db->query(
                    "UPDATE publicaciones SET likes = GREATEST(likes - 1, 0) WHERE idPublicacion = ?",
                    [$id]
                );
                
                $accion = 'unliked';
            } else {
                // Agregar like
                $this->db->query(
                    "INSERT INTO likes (idUsuario, idPublicacion) VALUES (?, ?)",
                    [$_SESSION['user']['idUsuario'], $id]
                );
                
                $this->db->query(
                    "UPDATE publicaciones SET likes = likes + 1 WHERE idPublicacion = ?",
                    [$id]
                );
                
                $accion = 'liked';
            }

            $post = $this->db->query(
                "SELECT likes FROM publicaciones WHERE idPublicacion = ?",
                [$id]
            )->find();

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'accion' => $accion,
                'likes' => $post['likes']
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al procesar like']);
        }
    }

    /**
     * Manejo de carga de imágenes
     */
    private function handleImageUpload($file)
    {
        if ($file['error'] !== 0 && $file['error'] !== 4) {
            http_response_code(400);
            echo json_encode(['error' => 'Error en la carga del archivo']);
            return false;
        }

        if ($file['error'] === 4) {
            return null; // No se subió archivo
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['error' => 'La imagen no puede exceder 5MB']);
            return false;
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipo de imagen no permitido']);
            return false;
        }

        $uploadDir = 'imagenes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $uploadPath;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar la imagen']);
            return false;
        }
    }

    /**
     * Manejo de carga de videos
     */
    private function handleVideoUpload($file)
    {
        if ($file['error'] !== 0 && $file['error'] !== 4) {
            http_response_code(400);
            echo json_encode(['error' => 'Error en la carga del archivo']);
            return false;
        }

        if ($file['error'] === 4) {
            return null; // No se subió archivo
        }

        if ($file['size'] > 50 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['error' => 'El video no puede exceder 50MB']);
            return false;
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, ['video/mp4', 'video/quicktime', 'video/x-msvideo'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipo de video no permitido']);
            return false;
        }

        $uploadDir = 'videos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $uploadPath;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar el video']);
            return false;
        }
    }

    /**
     * Verificar si es una solicitud JSON
     */
    private function isJson()
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') || 
               str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');
    }
}