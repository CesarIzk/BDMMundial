<?php

namespace Controles\Api;

use Controles\Models\Comentario;
use Core\App;

class ComentarioController
{
    protected $comentarioModel;

    public function __construct()
    {
        $this->comentarioModel = new Comentario();
    }

    /**
     * Obtener comentarios de una publicación (GET)
     */
    public function index($params)
    {
        $postId = $params['id'] ?? null;

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de publicación requerido']);
            return;
        }

        $comentarios = $this->comentarioModel->getByPost($postId);

        header('Content-Type: application/json');
        echo json_encode($comentarios);
    }

    /**
     * Crear nuevo comentario (POST)
     */
    public function store()
    {
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

        $idPublicacion = $_POST['idPublicacion'] ?? null;
        $contenido = trim($_POST['contenido'] ?? '');
        $idUsuario = $_SESSION['user']['idUsuario'];

        if (!$idPublicacion || empty($contenido)) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan datos']);
            return;
        }

        try {
            $this->comentarioModel->create($idPublicacion, $idUsuario, $contenido);

            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Comentario agregado']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear comentario']);
        }
    }

    /**
     * Eliminar un comentario (DELETE)
     */
    public function delete($params)
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return;
        }

        $idComentario = $params['id'] ?? null;
        $idUsuario = $_SESSION['user']['idUsuario'];

        if (!$idComentario) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de comentario requerido']);
            return;
        }

        $this->comentarioModel->delete($idComentario, $idUsuario);

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Comentario eliminado']);
    }
}
