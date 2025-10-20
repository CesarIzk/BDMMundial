<?php

// Railway usa MYSQL_URL en lugar de DATABASE_URL
$url = getenv('MYSQL_URL') ?: getenv('DATABASE_URL');

// Configuración local por defecto
if ($url === false) {
    return [
        'database' => [
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'railway', // 👈 Cambia esto al nombre de tu BD local
            'charset' => 'utf8mb4'
        ]
    ];
}

// Descompone la URL de Railway
$parts = parse_url($url);

return [
    'database' => [
        'host' => $parts['host'],
        'port' => $parts['port'] ?? 3306,
        'user' => $parts['user'],
        'password' => $parts['pass'],
        'dbname' => ltrim($parts['path'], '/'),
        'charset' => 'utf8mb4'
    ]
];