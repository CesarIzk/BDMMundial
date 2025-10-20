<?php

// 1. Lee la variable de entorno DATABASE_URL que provee Railway
$url = getenv('DATABASE_URL');

// 2. Si la variable NO existe (por si corres esto en tu PC local)
//    usa una configuración local por defecto.
if ($url === false) {
    return [
        'database' => [
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root', // Pon tu contraseña local aquí
            'dbname' => 'myapp', // Pon el nombre de tu BD local
            'charset' => 'utf8mb4'
        ]
    ];
}

// 3. Si SÍ existe (está en Railway), descompone la URL
$parts = parse_url($url);

// 4. Retorna el array de configuración que tu app espera
return [
    'database' => [
        'host' => $parts['host'],
        'port' => $parts['port'],
        'user' => $parts['user'],
        'password' => $parts['pass'],
        'dbname' => ltrim($parts['path'], '/'), // Quita el '/' del inicio
        'charset' => 'utf8mb4' // Asumiendo que usas utf8mb4
    ]
];