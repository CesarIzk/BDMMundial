<?php

namespace Core\Middleware;

class Admin
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos para acceder a esta sección";
            header('Location: /');
            exit;
        }
    }
}