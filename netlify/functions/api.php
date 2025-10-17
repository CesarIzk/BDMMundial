<?php
// api.php

/**
 * Esta función es el punto de entrada para todo el tráfico del sitio.
 * Su único trabajo es incluir el "front controller" de tu aplicación,
 * que es tu archivo index.php principal.
 */

// Define la ruta raíz del proyecto
define('PROJECT_ROOT', dirname(__DIR__, 2));

// Carga el punto de entrada de tu aplicación
require_once PROJECT_ROOT . '/public/index.php';