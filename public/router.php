<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Deja pasar archivos físicos (css, js, imágenes)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Redirige todo a index.php
require_once __DIR__ . '/index.php';
