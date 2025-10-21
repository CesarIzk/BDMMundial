<?php

namespace Controles\Models;

use Core\App;

class Publicacion
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /**
     * Obtener publicación por ID con datos del usuario
     */
    public function findById($id)
    {
        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.idPublicacion = ?',
            [$id]
        )->find();
    }

    /**
     * Obtener todas las publicaciones públicas con paginación
     */
    public function all($limit = 10, $offset = 0)
    {
        // ¡CORREGIDO! Faltaban los (int) casts
        $limit = (int) $limit;
        $offset = (int) $offset;

        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.estado = "publico"
             ORDER BY p.postdate DESC
             LIMIT ? OFFSET ?',
            [$limit, $offset]
        )->get();
    }

    /**
     * Obtener publicaciones destacadas (con más likes)
     */
    public function getFeatured($limit = 3)
    {
        // ¡CORREGIDO! Faltaba el (int) cast
        $limit = (int) $limit;

        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username, u.fotoPerfil 
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.estado = "publico"
             ORDER BY p.likes DESC, p.postdate DESC
             LIMIT ?',
            [$limit]
        )->get();
    }

    /**
     * Obtener publicaciones de un usuario específico
     */
    public function getByUser($idUsuario, $limit = 10, $offset = 0)
    {
        // ¡CORREGIDO! Faltaban los (int) casts
        $limit = (int) $limit;
        $offset = (int) $offset;

        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username, u.fotoPerfil
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.idUsuario = ? AND p.estado = "publico"
             ORDER BY p.postdate DESC
             LIMIT ? OFFSET ?',
            [$idUsuario, $limit, $offset]
        )->get();
    }

    /**
     * Crear publicación
     */
    public function create($data)
    {
        $this->db->query(
            'INSERT INTO publicaciones (idUsuario, texto, tipoContenido, rutamulti, estado)
             VALUES (?, ?, ?, ?, ?)',
            [
                $data['idUsuario'],
                $data['texto'],
                $data['tipoContenido'] ?? null,
                $data['rutamulti'] ?? null,
                'publico'
            ]
        );

        $lastId = $this->db->connection->lastInsertId();
        return $this->findById($lastId);
    }

    /**
     * Actualizar publicación
     */
    public function update($id, $data)
    {
        $fields = [];
        $values = [];

        $allowedFields = ['texto', 'tipoContenido', 'rutamulti', 'estado'];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }

        if (empty($fields)) {
            return $this->findById($id);
        }

        $values[] = $id;

        $this->db->query(
            'UPDATE publicaciones SET ' . implode(', ', $fields) . ' WHERE idPublicacion = ?',
            $values
        );

        return $this->findById($id);
    }

    /**
     * Agregar like a una publicación
     */
    public function addLike($idPublicacion)
    {
        $this->db->query(
            'UPDATE publicaciones SET likes = likes + 1 WHERE idPublicacion = ?',
            [$idPublicacion]
        );

        return $this->findById($idPublicacion);
    }

    /**
     * Quitar like de una publicación
     */
    public function removeLike($idPublicacion)
    {
        $this->db->query(
            'UPDATE publicaciones SET likes = GREATEST(likes - 1, 0) WHERE idPublicacion = ?',
            [$idPublicacion]
        );

        return $this->findById($idPublicacion);
    }

    /**
     * Eliminar publicación
     */
    public function delete($id)
    {
        $this->db->query(
            'DELETE FROM publicaciones WHERE idPublicacion = ?',
            [$id]
        );

        return true;
    }

    /**
     * Contar total de publicaciones públicas
     */
    public function count()
    {
        return $this->db->query(
            'SELECT COUNT(*) as count FROM publicaciones WHERE estado = "publico"'
        )->find()['count'];
    }

    /**
     * Contar publicaciones de un usuario
     */
    public function countByUser($idUsuario)
    {
        return $this->db->query(
            'SELECT COUNT(*) as count FROM publicaciones WHERE idUsuario = ? AND estado = "publico"',
            [$idUsuario]
        )->find()['count'];
    }

    /**
     * Buscar publicaciones (por texto)
     */
    public function search($query, $limit = 10)
    {
        // ¡CORREGIDO! Faltaba el (int) cast
        $limit = (int) $limit;

        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username, u.fotoPerfil
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.estado = "publico" AND p.texto LIKE ?
             ORDER BY p.postdate DESC
             LIMIT ?',
            ['%' . $query . '%', $limit]
        )->get();
    }

    /**
     * Obtener todas las publicaciones (incluyendo privadas/bloqueadas) para admin
     */
    public function allForAdmin($limit = 10, $offset = 0)
    {
        // ¡¡CORRECCIÓN DE SEGURIDAD CRÍTICA!!
        // Tu versión anterior era vulnerable a Inyección SQL.
        // Esta versión usa parámetros '?' y (int) casts para ser segura.
        $limit = (int) $limit;
        $offset = (int) $offset;

        return $this->db->query(
            'SELECT p.*, u.Nombre, u.username
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             ORDER BY p.postdate DESC
             LIMIT ? OFFSET ?',
            [$limit, $offset]
        )->get();
    }

    /**
     * Contar todas las publicaciones para admin
     */
    public function countAll()
    {
        return $this->db->query(
            'SELECT COUNT(*) as count FROM publicaciones'
        )->find()['count'];
    }
    
    // --- MÉTODOS AÑADIDOS PARA DASHBOARD Y REPORTES ---

    /**
     * Contar publicaciones nuevas de hoy
     */
    public function countNuevasHoy()
    {
        return $this->db->query(
            "SELECT COUNT(*) as count FROM publicaciones WHERE DATE(postdate) = CURDATE()"
        )->find()['count'];
    }

    /**
     * Contar publicaciones por estado
     */
    public function countByEstado($estado = 'oculto')
    {
        return $this->db->query(
            "SELECT COUNT(*) as count FROM publicaciones WHERE estado = ?",
            [$estado]
        )->find()['count'];
    }

    /**
     * Obtener publicaciones recientes (para log de actividad)
     */
    public function getRecientes($limit = 5)
    {
        return $this->db->query(
            "SELECT p.postdate, u.username 
             FROM publicaciones p 
             JOIN users u ON p.idUsuario = u.idUsuario 
             ORDER BY p.postdate DESC LIMIT ?",
            [(int)$limit]
        )->get();
    }

    /**
     * Obtener datos para gráfico de 7 días
     */
    public function getNuevasPublicaciones7Dias()
    {
        return $this->db->query(
            "SELECT DATE(postdate) as dia, COUNT(*) as total 
             FROM publicaciones 
             WHERE postdate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
             GROUP BY dia ORDER BY dia ASC"
        )->get();
    }

    /**
     * Obtener stats generales de publicaciones en rango
     */
    public function getReporteGeneralRango($inicio, $fin)
    {
        return $this->db->query(
            "SELECT 
                COUNT(*) as totalPublicaciones, 
                SUM(likes) as totalInteracciones, 
                AVG(likes) as promedioLikes 
             FROM publicaciones 
             WHERE postdate BETWEEN ? AND ?",
            [$inicio, $fin]
        )->find();
    }
    
    /**
     * Obtener stats de tipos de contenido en rango
     */
    public function getContenidoStatsRango($inicio, $fin)
    {
        $data = $this->db->query(
            "SELECT 
                CASE 
                    WHEN tipoContenido = 'imagen' THEN 'imagen'
                    WHEN tipoContenido = 'video' THEN 'video'
                    ELSE 'texto' 
                END as tipo, 
                COUNT(*) as total 
             FROM publicaciones 
             WHERE postdate BETWEEN ? AND ?
             GROUP BY tipo",
            [$inicio, $fin]
        )->get();
        
        // Convertir de [ ['tipo' => 'imagen', 'total' => 10] ] a ['imagen' => 10]
        return array_column($data, 'total', 'tipo');
    }
    
    /**
     * Obtener top publicaciones populares en rango
     */
    public function getTopPopularesRango($inicio, $fin, $limit = 10)
    {
        return $this->db->query(
            "SELECT p.texto, p.likes, u.username 
             FROM publicaciones p
             JOIN users u ON p.idUsuario = u.idUsuario
             WHERE p.postdate BETWEEN ? AND ?
             ORDER BY p.likes DESC
             LIMIT ?",
            [$inicio, $fin, (int)$limit]
        )->get();
    }

    /**
     * Obtener reporte detallado por día (simplificado)
     */
    public function getReporteDetalladoRango($inicio, $fin)
    {
        $posts = $this->db->query(
            "SELECT 
                DATE(postdate) as fecha, 
                COUNT(*) as nuevasPublicaciones, 
                SUM(likes) as totalLikes, 
                COUNT(DISTINCT idUsuario) as usuariosActivos 
             FROM publicaciones 
             WHERE postdate BETWEEN ? AND ?
             GROUP BY fecha 
             ORDER BY fecha ASC",
            [$inicio, $fin]
        )->get();
        
        return $posts;
    }
}