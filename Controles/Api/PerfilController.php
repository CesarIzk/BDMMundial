<?php

namespace Controles\Api;

use Controles\Models\User;
use Cloudinary\Cloudinary;

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

    $id = $_SESSION['user']['idUsuario'];

    // 🔄 Obtener datos actualizados desde la base de datos
    $user = $this->userModel->findById($id);

    // ✅ Actualizar sesión (para que quede sincronizada)
    $_SESSION['user'] = $user;
error_log("🧠 PERFIL - ID USUARIO: " . $_SESSION['user']['idUsuario']);
error_log("🧠 PERFIL - RESULTADO BD: " . print_r($user, true));

    // ✅ Pasar la variable con el nombre correcto para la vista
    return view('perfil.php', ['userData' => $user]);
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
        $_SESSION['error'] = "Usuario no encontrado.";
        return redirect('/configuracion');
    }

    // Campos que se pueden actualizar
    $camposPermitidos = [
        'Nombre', 'biografia', 'fechaNacimiento',
        'genero', 'ciudad', 'pais', 'email'
    ];

    $data = [];
error_log("==== DEBUG PERFIL ====");
error_log("POST: " . print_r($_POST, true));
error_log("USERACTUAL: " . print_r($userActual, true));

    foreach ($camposPermitidos as $campo) {
        if (isset($_POST[$campo])) {
            $nuevo = trim($_POST[$campo]);
            $actual = isset($userActual[$campo]) ? trim((string)$userActual[$campo]) : '';

            // 🔹 Normalizar fecha
            if ($campo === 'fechaNacimiento') {
                $actual = substr($actual, 0, 10);
            }

            // 🔹 Comparar de forma insensible a mayúsculas
            if ($nuevo !== '' && strcasecmp($nuevo, $actual) !== 0) {
                $data[$campo] = $nuevo;
            }
        }
    }

    if (empty($data)) {
        $_SESSION['info'] = "No se detectaron cambios en tu perfil.";
        return redirect('/configuracion');
    }

    // Actualizar en BD
// Actualizar en BD
$this->userModel->update($id, $data);

// ✅ Recargar información completa del usuario desde la BD
$usuarioActualizado = $this->userModel->findById($id);

// ✅ Actualizar la sesión con los nuevos datos
$_SESSION['user'] = $usuarioActualizado;

// Mensaje de éxito
$_SESSION['success'] = "Perfil actualizado correctamente.";


    return redirect('/perfil');
}

    /**
     * Cambiar contraseña
     */
    public function changePassword()
{
    $this->authorizeUser();

    $id = $_SESSION['user']['idUsuario'];
    $actual = $_POST['actual'] ?? '';
    $nueva = $_POST['nueva'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nueva !== $confirmar) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        return redirect('/configuracion');
    }

    $user = $this->userModel->findById($id);

    if (!$user || !password_verify($actual, $user['contrasena'])) {
        $_SESSION['error'] = "Contraseña actual incorrecta.";
        return redirect('/configuracion');
    }

    $this->userModel->update($id, [
        'contrasena' => password_hash($nueva, PASSWORD_DEFAULT)
    ]);

    $_SESSION['success'] = "Contraseña actualizada correctamente.";
    return redirect('/configuracion');
}


    /**
     * Actualizar avatar
     */
public function updateAvatar()
{
    $this->authorizeUser();
    $id = $_SESSION['user']['idUsuario'];

    if (empty($_FILES['avatar']['name'])) {
        $_SESSION['error'] = "No se seleccionó ningún archivo.";
        return redirect('/configuracion');
    }

    if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = "Error al subir el archivo (código: " . $_FILES['avatar']['error'] . ").";
        return redirect('/configuracion');
    }

    // ✅ Validar tipo y tamaño
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    $fileType = mime_content_type($_FILES['avatar']['tmp_name']);
    if (!isset($allowedTypes[$fileType])) {
        $_SESSION['error'] = "Formato no permitido. Solo JPG, PNG, GIF o WEBP.";
        return redirect('/configuracion');
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($_FILES['avatar']['size'] > $maxSize) {
        $_SESSION['error'] = "El archivo es demasiado grande. Máximo 5MB.";
        return redirect('/configuracion');
    }

    // 🧾 Generar nombre único
    $extension = $allowedTypes[$fileType];
    $fileName = 'avatar_' . $id . '_' . time() . '.' . $extension;

    // 🌐 Si estamos en Railway (usa Cloudinary)
    if (isset($_ENV['CLOUDINARY_URL']) && !empty($_ENV['CLOUDINARY_URL'])) {
        try {
            $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);

            // Subir a carpeta personalizada del usuario
            $upload = $cloudinary->uploadApi()->upload($_FILES['avatar']['tmp_name'], [
                'folder' => "mundialfan/usuarios/$id/avatar",
                'public_id' => pathinfo($fileName, PATHINFO_FILENAME),
                'overwrite' => true,
                'resource_type' => 'image'
            ]);

            $rutaRelativa = $upload['secure_url'];
            error_log("☁️ Avatar subido a Cloudinary: " . $rutaRelativa);
        } catch (\Exception $e) {
            error_log("❌ Error Cloudinary avatar: " . $e->getMessage());
            $_SESSION['error'] = "Error al subir la imagen a Cloudinary.";
            return redirect('/configuracion');
        }
    } else {
        // 🗂️ Guardar localmente en desarrollo
        $userDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/users/' . $id . '/avatar/';
        if (!is_dir($userDir)) mkdir($userDir, 0755, true);

        $rutaCompleta = $userDir . $fileName;
        $rutaRelativa = '/uploads/users/' . $id . '/avatar/' . $fileName;

        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $rutaCompleta)) {
            $_SESSION['error'] = "No se pudo guardar la imagen localmente.";
            return redirect('/configuracion');
        }
    }

    // 🧹 Borrar imagen anterior (solo local)
    if (!empty($_SESSION['user']['fotoPerfil'])) {
        $anterior = $_SESSION['user']['fotoPerfil'];
        if (strpos($anterior, 'cloudinary.com') === false) {
            $rutaLocal = $_SERVER['DOCUMENT_ROOT'] . $anterior;
            if (file_exists($rutaLocal)) {
                @unlink($rutaLocal);
            }
        }
    }

    // 💾 Actualizar base de datos
    $this->userModel->update($id, ['fotoPerfil' => $rutaRelativa]);

    // 🔄 Actualizar sesión
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