<?php
require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Cloudinary;

// Copiar variables desde $_SERVER (en caso de Railway)
foreach ($_SERVER as $key => $value) {
    if (str_starts_with($key, 'CLOUDINARY_')) {
        $_ENV[$key] = $value;
    }
}

try {
    $cloud = new Cloudinary([
        'cloud' => [
            'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? $_ENV['CLOUDINARY_NAME'] ?? '',
            'api_key'    => $_ENV['CLOUDINARY_API_KEY'] ?? $_ENV['CLOUDINARY_KEY'] ?? '',
            'api_secret' => $_ENV['CLOUDINARY_API_SECRET'] ?? $_ENV['CLOUDINARY_APISECRET'] ?? ''
        ]
    ]);

    // 🔹 Subir imagen de ejemplo (si existe en local)
    $sample = __DIR__ . '/public/imagenes/like.png';
    if (!file_exists($sample)) {
        echo "⚠️ Archivo de prueba no encontrado: $sample";
        exit;
    }

    $upload = $cloud->uploadApi()->upload($sample, [
        'folder' => 'mundialfan/test'
    ]);

    echo "✅ Cloudinary funcionando correctamente.<br>";
    echo "URL subida: <a href='{$upload['secure_url']}' target='_blank'>{$upload['secure_url']}</a>";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
