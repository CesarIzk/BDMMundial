<?php

namespace Controles\Api;

use Controles\Models\User;

class PerfilController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

   
    public function show()
    {
        $this->authorizeUser();

        $user = $_SESSION['user'];
        return view('perfil.php', ['user' => $user]);
    }

    /**
     * Mostrar configuración del perfil
     */
    public function index()
    {
        $this->authorizeUser();

        $user = $_SESSION['user'];
        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        $info = $_SESSION['info'] ?? null;
        unset($_SESSION['success'], $_SESSION['error'], $_SESSION['info']);

        return view('configuracion.php', [
            'userData' => $user,
            'success' => $success,
            'error' => $error,
            'info' => $info
        ]);
    }

    /**
     * Actualizar datos del usuario
     */
    public function update()
    {
        $this->authorizeUser();

        $id = $_SESSION['user']['idUsuario'];
        $userActual = $this->userModel->findById($id);

        if (!$userActual) {
            $_SESSION['error'] = "Usuario no encontrado";
            return redirect('/configuracion');
        }

        // Campos permitidos para actualizar
        $camposPermitidos = [
            'Nombre', 'biografia', 'fechaNacimiento',
            'genero', 'ciudad', 'pais', 'email'
        ];

        $data = [];

        foreach ($camposPermitidos as $campo) {
            if (isset($_POST[$campo]) && $_POST[$campo] !== '' && $_POST[$campo] != $userActual[$campo]) {
                $data[$campo] = trim($_POST[$campo]);
            }
        }

        if (empty($data)) {
            $_SESSION['info'] = "No se detectaron cambios en tu perfil.";
            return redirect('/configuracion');
        }

        // Actualizamos solo los campos modificados
        $this->userModel->update($id, $data);

        // Refrescamos la sesión del usuario
        $_SESSION['user'] = array_merge($_SESSION['user'], $data);

        $_SESSION['success'] = "Perfil actualizado correctamente.";
        return redirect('/perfil');
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword()
    {
        $this->authorizeUser();

        $id = $_SESSION['user']['id'];
        $actual = $_POST['actual'] ?? '';
        $nueva = $_POST['nueva'] ?? '';

        $user = $this->userModel->findById($id);

        if (!password_verify($actual, $user['password'])) {
            $_SESSION['error'] = "Contraseña actual incorrecta";
            return redirect('/configuracion');
        }

        $this->userModel->update($id, ['password' => password_hash($nueva, PASSWORD_DEFAULT)]);
        $_SESSION['success'] = "Contraseña actualizada correctamente";
        return redirect('/configuracion');
    }

    /**
     * Actualizar avatar
     */
   public function updateAvatar()
{
    $this->authorizeUser();

    $id = $_SESSION['user']['idUsuario'];

    // Verificar que se subió un archivo
    if (empty($_FILES['avatar']['name'])) {
        $_SESSION['error'] = "No se seleccionó ningún archivo";
        return redirect('/configuracion');
    }

    // Validar errores
    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Error al subir el archivo. Código: " . $_FILES['avatar']['error'];
        return redirect('/configuracion');
    }

    // Validar tipo y tamaño
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($_FILES['avatar']['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = "Solo se permiten imágenes JPG, PNG, GIF o WEBP.";
        return redirect('/configuracion');
    }

    $maxSize = 5 * 1024 * 1024;
    if ($_FILES['avatar']['size'] > $maxSize) {
        $_SESSION['error'] = "El archivo es demasiado grande. Máximo 5MB.";
        return redirect('/configuracion');
    }

    // Crear carpeta si no existe
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generar nombre único
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'user_' . $id . '_' . time() . '.' . $extension;
    $rutaCompleta = $uploadDir . $nombreArchivo;
    $rutaRelativa = '/uploads/avatars/' . $nombreArchivo;

    // Mover el archivo
    if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $rutaCompleta)) {
        $_SESSION['error'] = "Error al guardar la imagen.";
        return redirect('/configuracion');
    }

    // Eliminar foto anterior si existe
    if (!empty($_SESSION['user']['fotoPerfil'])) {
        $anterior = $_SERVER['DOCUMENT_ROOT'] . $_SESSION['user']['fotoPerfil'];
        if (file_exists($anterior)) {
            @unlink($anterior);
        }
    }

    // Actualizar base de datos
    $this->userModel->update($id, ['fotoPerfil' => $rutaRelativa]);

    // Actualizar sesión
    $_SESSION['user']['fotoPerfil'] = $rutaRelativa;

    $_SESSION['success'] = "Foto de perfil actualizada correctamente.";
    return redirect('/perfil');
}


    /**
     * Desactivar usuario (baja voluntaria)
     */
    public function deactivate()
    {
        $this->authorizeUser();

        $id = $_SESSION['user']['id'];
        $this->userModel->changeStatus($id, 'inactivo');

        session_destroy();
        return redirect('/');
    }

    /**
     * Validar usuario autenticado
     */
    private function authorizeUser()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Debes iniciar sesión primero";
            header('Location: /login');
            exit;
        }
    }
}