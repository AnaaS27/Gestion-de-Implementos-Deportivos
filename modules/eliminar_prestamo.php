<?php
session_start();
// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

require_once '../conexion.php'; // Archivo de conexión a la base de datos

// Verificar si se recibió el ID del préstamo
if (!isset($_GET['id'])) {
    header("Location: gestionar_prestamos.php?error=ID del préstamo no proporcionado.");
    exit;
}

$id_prestamo = intval($_GET['id']);

// Validar si el préstamo existe
$query = "SELECT * FROM prestamo WHERE id_prestamo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_prestamo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: gestionar_prestamos.php?error=El préstamo no existe.");
    exit;
}

// Eliminar el préstamo
$delete_query = "DELETE FROM prestamo WHERE id_prestamo = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $id_prestamo);

if ($delete_stmt->execute()) {
    header("Location: gestionar_prestamos.php?mensaje=Préstamo eliminado correctamente.");
    exit;
} else {
    echo "Error al eliminar el préstamo: " . $delete_stmt->error;
    exit;
}
?>
