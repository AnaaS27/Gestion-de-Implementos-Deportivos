<?php
$servername = "localhost"; // Cambia por tu servidor (si usas hosting puede ser diferente)
$username = "root"; // Usuario de tu base de datos
$password = ""; // Contraseña de tu base de datos
$dbname = "gestion_deportiva"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

