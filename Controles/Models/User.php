<?php

namespace Controles\Models;

use Core\App;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /**
     * Buscar usuario por email
     */
    public function findByEmail($email)
    {
        return $this->db->query(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        )->find();
    }

    /**
     * Buscar usuario por ID
     */
    public function findById($id)
    {
        return $this->db->query(
            'SELECT * FROM users WHERE idUsuario = ?',
            [$id]
        )->find();
    }

    /**
     * Buscar usuario por username
     */
    public function findByUsername($username)
    {
        return $this->db->query(
            'SELECT * FROM users WHERE username = ?',
            [$username]
        )->find();
    }

    /**
     * Obtener todos los usuarios con paginación
     */
public function all($limit = 10, $offset = 0)
    {
        // ¡AÑADE ESTAS DOS LÍNEAS!
        $limit = (int) $limit;
        $offset = (int) $offset;

        return $this->db->query(
            'SELECT * FROM users ORDER BY fechaRegistro DESC LIMIT ? OFFSET ?',
            [$limit, $offset]
        )->get();
    }

/**
     * Obtener usuarios activos
     */
    public function getActive($limit = 10, $offset = 0)
    {
        // ¡AÑADE ESTAS DOS LÍNEAS TAMBIÉN!
        $limit = (int) $limit;
        $offset = (int) $offset;

        return $this->db->query(
            'SELECT * FROM users WHERE estado = ? ORDER BY fechaRegistro DESC LIMIT ? OFFSET ?',
            ['activo', $limit, $offset]
        )->get();
    }

    /**
     * Crear nuevo usuario
     */
    public function create($data)
    {
        $this->db->query(
            'INSERT INTO users (Nombre, email, username, contrasena, pais, rol, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $data['Nombre'],
                $data['email'],
                $data['username'],
                $data['contrasena'],
                $data['pais'],
                $data['rol'] ?? 'usuario',
                $data['estado'] ?? 'activo'
            ]
        );

        return $this->findByEmail($data['email']);
    }

    /**
     * Actualizar usuario
     */
    public function update($id, $data)
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;

        $this->db->query(
            'UPDATE users SET ' . implode(', ', $fields) . ' WHERE idUsuario = ?',
            $values
        );

        return $this->findById($id);
    }

    /**
     * Cambiar estado del usuario
     */
    public function changeStatus($id, $estado)
    {
        return $this->update($id, ['estado' => $estado]);
    }

    /**
     * Cambiar rol del usuario
     */
    public function changeRole($id, $rol)
    {
        return $this->update($id, ['rol' => $rol]);
    }

    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        $this->db->query(
            'DELETE FROM users WHERE idUsuario = ?',
            [$id]
        );

        return true;
    }

    /**
     * Contar total de usuarios
     */
    public function count()
    {
        return $this->db->query('SELECT COUNT(*) as count FROM users')->find()['count'];
    }

    /**
     * Contar usuarios activos
     */
    public function countActive()
    {
        return $this->db->query(
            'SELECT COUNT(*) as count FROM users WHERE estado = ?',
            ['activo']
        )->find()['count'];
    }

    /**
     * Verificar si el email existe
     */
    public function emailExists($email)
    {
        return (bool) $this->db->query(
            'SELECT idUsuario FROM users WHERE email = ?',
            [$email]
        )->find();
    }

    /**
     * Verificar si el username existe
     */
    public function usernameExists($username)
    {
        return (bool) $this->db->query(
            'SELECT idUsuario FROM users WHERE username = ?',
            [$username]
        )->find();
    }

/**
     * Usuarios más activos (para reportes)
     */
    public function getTopUsers($limit = 10)
    {
        // ¡Y AÑADE ESTA LÍNEA AQUÍ!
        $limit = (int) $limit;

        return $this->db->query(
            'SELECT u.idUsuario, u.Nombre, u.username, COUNT(p.idPublicacion) as postCount
             FROM users u
             LEFT JOIN publicaciones p ON u.idUsuario = p.idUsuario
             GROUP BY u.idUsuario
             ORDER BY postCount DESC
             LIMIT ?',
            [$limit]
        )->get();
    }
}