<?php
// api.php

// 1. Calcula la ruta al archivo index.php
$indexPath = dirname(__DIR__, 2) . '/public/index.php';

// 2. Comprueba si el archivo realmente existe en esa ruta
if (!file_exists($indexPath)) {
    // Si no existe, detiene la ejecución y muestra un error claro.
    http_response_code(500); // Código de error del servidor
    die("ERROR EN api.php: No se pudo encontrar el archivo index.php en la ruta: " . $indexPath);
}

// 3. Si el archivo SÍ existe, lo carga.
require_once $indexPath;