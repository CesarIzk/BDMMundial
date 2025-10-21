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

        $id = $_SESSION['user']['id'];
        if (!empty($_FILES['avatar']['name'])) {
            $path = '/uploads/avatars/' . basename($_FILES['avatar']['name']);
            move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . '/../../../public' . $path);
            $this->userModel->update($id, ['avatar' => $path]);
            $_SESSION['user']['avatar'] = $path;
        }

        $_SESSION['success'] = "Avatar actualizado correctamente";
        return view('/configuracion');
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
