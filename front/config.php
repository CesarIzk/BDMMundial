<<<<<<< HEAD
<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mundiales_redsocial";

// Crear conexión
$conn = new mysqli($host, $user, $password, $db);

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
=======
<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "mundiales_redsocial";

// Crear conexión
$conn = new mysqli($host, $user, $password, $db);

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
>>>>>>> 5aa30f80412a593f2ccf04a3308e03e484a024b3
?>