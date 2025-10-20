<?php

namespace Controles\Api;

use Core\App;

class AuthController
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve('Core\Database');
    }

    /**
     * Procesar login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/');
        }

        $email = trim($_POST['correo'] ?? '');
        $password = trim($_POST['contrasena'] ?? '');

        // Validar que no estén vacíos
        if (empty($email) || empty($password)) {
            return $this->redirectWithError('Por favor completa todos los campos');
        }

        // Buscar usuario por email
        $user = $this->db->query(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        )->find();

        if (!$user) {
            return $this->redirectWithError('Email o contraseña incorrectos');
        }

        // Verificar contraseña (comparación simple)
        if ($user['contrasena'] !== $password) {
            return $this->redirectWithError('Email o contraseña incorrectos');
        }

        // Verificar si usuario está activo
        if ($user['estado'] !== 'activo') {
            return $this->redirectWithError('Tu cuenta ha sido desactivada');
        }

        // Crear sesión
        session_start();
        $_SESSION['user'] = [
            'idUsuario' => $user['idUsuario'],
            'Nombre' => $user['Nombre'],
            'email' => $user['email'],
            'username' => $user['username'],
            'rol' => $user['rol'],
            'fotoPerfil' => $user['fotoPerfil']
        ];

        // Actualizar última actividad
        $this->db->query(
            'UPDATE users SET ultimaActividad = NOW() WHERE idUsuario = ?',
            [$user['idUsuario']]
        );

        // Redirigir según rol
        if ($user['rol'] === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/');
    }

    /**
     * Procesar registro
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/');
        }

        $nombreCom = trim($_POST['nombreCom'] ?? '');
        $email = trim($_POST['correo'] ?? '');
        $contrasena = trim($_POST['contrasena'] ?? '');
        $contrasena2 = trim($_POST['contrasena2'] ?? '');
        $nacionalidad = trim($_POST['nacionalidad'] ?? '');

        // Validaciones
        if (empty($nombreCom) || empty($email) || empty($contrasena) || empty($nacionalidad)) {
            return $this->redirectWithError('Por favor completa todos los campos');
        }

        if (strlen($contrasena) < 4) {
            return $this->redirectWithError('La contraseña debe tener al menos 4 caracteres');
        }

        if ($contrasena !== $contrasena2) {
            return $this->redirectWithError('Las contraseñas no coinciden');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->redirectWithError('El email no es válido');
        }

        // Verificar si el email ya existe
        $existingUser = $this->db->query(
            'SELECT idUsuario FROM users WHERE email = ?',
            [$email]
        )->find();

        if ($existingUser) {
            return $this->redirectWithError('El email ya está registrado');
        }

        // Crear username automático
        $username = strtolower(str_replace(' ', '', $nombreCom)) . rand(100, 999);

        // Verificar que el username sea único
        while ($this->db->query('SELECT idUsuario FROM users WHERE username = ?', [$username])->find()) {
            $username = strtolower(str_replace(' ', '', $nombreCom)) . rand(1000, 9999);
        }

        // Insertar usuario sin hash
        try {
            $this->db->query(
                'INSERT INTO users (Nombre, email, username, contrasena, pais, rol, estado)
                 VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$nombreCom, $email, $username, $contrasena, $nacionalidad, 'usuario', 'activo']
            );

            // Obtener el usuario creado
            $newUser = $this->db->query(
                'SELECT * FROM users WHERE email = ?',
                [$email]
            )->find();

            // Crear sesión automáticamente
            session_start();
            $_SESSION['user'] = [
                'idUsuario' => $newUser['idUsuario'],
                'Nombre' => $newUser['Nombre'],
                'email' => $newUser['email'],
                'username' => $newUser['username'],
                'rol' => $newUser['rol'],
                'fotoPerfil' => $newUser['fotoPerfil']
            ];

            return redirect('/');
        } catch (\Exception $e) {
            return $this->redirectWithError('Error al registrar el usuario');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        session_start();
        session_destroy();
        return redirect('/');
    }

    /**
     * Auxiliar para redirigir con error
     */
    private function redirectWithError($message)
    {
        session_start();
        $_SESSION['error'] = $message;
        return redirect('/#authModal');
    }
}