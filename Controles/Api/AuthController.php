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

        // Verificar contraseña
        if ($user['contrasena'] !== $password) {
            return $this->redirectWithError('Email o contraseña incorrectos');
        }

        // Verificar si usuario está activo
        if ($user['estado'] !== 'activo') {
            return $this->redirectWithError('Tu cuenta ha sido desactivada');
        }

        // ✅ CRÍTICO: Regenerar ID de sesión para prevenir session fixation
        session_regenerate_id(true);

        // ✅ Crear sesión con todos los datos necesarios
        $_SESSION['user'] = [
            'idUsuario' => (int)$user['idUsuario'],
            'Nombre' => $user['Nombre'],
            'email' => $user['email'],
            'username' => $user['username'],
            'rol' => $user['rol'],
            'fotoPerfil' => $user['fotoPerfil'],
            'estado' => $user['estado']
        ];

        // ✅ Marcar tiempo de login
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        // 🔍 DEBUG LOG
        error_log("=== LOGIN EXITOSO ===");
        error_log("User ID: " . $user['idUsuario']);
        error_log("Session ID: " . session_id());
        error_log("Session data: " . print_r($_SESSION['user'], true));

        // Actualizar última actividad en BD
        try {
            $this->db->query(
                'UPDATE users SET ultimaActividad = NOW() WHERE idUsuario = ?',
                [$user['idUsuario']]
            );
        } catch (\Exception $e) {
            error_log("Error actualizando última actividad: " . $e->getMessage());
        }

        // ✅ Asegurar que la sesión se escriba antes de redirigir
        session_write_close();
        session_start(); // Reabrir para la siguiente petición

        // Redirigir según rol
        if ($user['rol'] === 'admin') {
            return redirect('/admin/usuarios');
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

        // Insertar usuario
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

            if (!$newUser) {
                return $this->redirectWithError('Error al crear la cuenta');
            }

            // ✅ Regenerar ID de sesión
            session_regenerate_id(true);

            // ✅ Crear sesión automáticamente
            $_SESSION['user'] = [
                'idUsuario' => (int)$newUser['idUsuario'],
                'Nombre' => $newUser['Nombre'],
                'email' => $newUser['email'],
                'username' => $newUser['username'],
                'rol' => $newUser['rol'],
                'fotoPerfil' => $newUser['fotoPerfil'],
                'estado' => $newUser['estado']
            ];

            // ✅ Marcar tiempo de registro
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();

            // 🔍 DEBUG LOG
            error_log("=== REGISTRO EXITOSO ===");
            error_log("User ID: " . $newUser['idUsuario']);
            error_log("Session ID: " . session_id());

            // ✅ Asegurar que la sesión se escriba
            session_write_close();
            session_start();

            return redirect('/');

        } catch (\Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            return $this->redirectWithError('Error al registrar el usuario');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // ✅ Limpiar datos de sesión
        $_SESSION = [];

        // ✅ Destruir la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // ✅ Destruir sesión
        session_destroy();

        error_log("=== LOGOUT EXITOSO ===");

        return redirect('/');
    }

    /**
     * Auxiliar para redirigir con error
     */
    private function redirectWithError($message)
    {
        $_SESSION['error'] = $message;
        
        // ✅ Asegurar que el error se guarde en sesión
        session_write_close();
        session_start();
        
        header('Location: /#authModal');
        exit;
    }
}