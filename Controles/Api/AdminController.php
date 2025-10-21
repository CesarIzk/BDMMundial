<?php

namespace Controles\Api;

use Controles\Models\User;

class AdminController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Mostrar listado de usuarios
     */
    public function users()
    {
        $this->authorizeAdmin();

        $usuarios = $this->userModel->all(100); // Ajusta límite
        return view('admin/usuarios.php', ['usuarios' => $usuarios]);
    }

    /**
     * Dar de baja a un usuario
     */
    public function deactivateUser($id)
    {
        $this->authorizeAdmin();

        $user = $this->userModel->changeStatus($id, 'inactivo');

        $_SESSION['success'] = "Usuario {$user['Nombre']} ha sido dado de baja";
        return redirect('/admin/usuarios');
    }

    /**
     * Reactivar usuario
     */
    public function activateUser($id)
    {
        $this->authorizeAdmin();

        $user = $this->userModel->changeStatus($id, 'activo');

        $_SESSION['success'] = "Usuario {$user['Nombre']} ha sido reactivado";
        return redirect('/admin/usuarios');
    }

    /**
     * Validar que el admin esté logueado
     */
    private function authorizeAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos para acceder a esta sección";
            header('Location: /');
            exit;
        }
    }
}
