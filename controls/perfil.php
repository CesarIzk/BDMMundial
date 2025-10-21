<?php

// Asegurarnos de que el usuario esté logueado
if (!isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

// Pasar los datos de sesión a la vista
$userData = $_SESSION['user'];

return view("perfil.php", compact('userData'));
