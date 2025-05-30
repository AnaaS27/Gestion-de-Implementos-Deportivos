<?php
$host = 'db'; // Este nombre es el nombre del servicio MySQL en docker-compose
$usuario = 'usuario';
$clave = 'clave123';
$bd = 'gestion_deportiva';

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


