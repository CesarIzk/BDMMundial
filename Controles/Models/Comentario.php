<?php

namespace Controles\Models;

use Core\App;

class Comentario
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /**
     * Obtener todos los comentarios de una publicación
     */
    public function getByPost($idPublicacion)
    {
        return $this->db->query(
            "SELECT c.*, u.username, u.fotoPerfil 
             FROM comentarios c
             JOIN users u ON c.idUsuario = u.idUsuario
             WHERE c.idPublicacion = ?
             ORDER BY c.fecha ASC",
            [$idPublicacion]
        )->get();
    }

    /**
     * Crear nuevo comentario
     */
    public function create($idPublicacion, $idUsuario, $contenido)
    {
        $this->db->query(
            "INSERT INTO comentarios (idPublicacion, idUsuario, contenido)
             VALUES (?, ?, ?)",
            [$idPublicacion, $idUsuario, htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8')]
        );
    }

    /**
     * Eliminar comentario
     */
    public function delete($idComentario, $idUsuario)
    {
        $this->db->query(
            "DELETE FROM comentarios WHERE idComentario = ? AND idUsuario = ?",
            [$idComentario, $idUsuario]
        );
    }

    /**
     * Contar comentarios de una publicación
     */
    public function countByPost($idPublicacion)
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total FROM comentarios WHERE idPublicacion = ?",
            [$idPublicacion]
        )->find();

        return $result ? (int)$result['total'] : 0;
    }
}
