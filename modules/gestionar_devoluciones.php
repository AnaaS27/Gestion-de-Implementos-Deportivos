<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de Administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Asegúrate de que la conexión es correcta

// Obtener las devoluciones con información del préstamo y del implemento
$query = "SELECT d.id_devolucion, p.id_prestamo, i.nombre AS implemento, d.fecha_devolucion, d.estado, d.observaciones 
          FROM devoluciones d
          JOIN prestamo p ON d.id_prestamo = p.id_prestamo
          JOIN implemento i ON p.id_implemento = i.id_implemento";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Devoluciones</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Gestión de Devoluciones</h2>

        <!-- Botón para agregar devolución -->
        <div class="text-center mb-3">
            <a href="registrar_devolucion.php" class="btn btn-primary">Registrar Devolución</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Préstamo</th>
                    <th>Implemento</th>
                    <th>Fecha de Devolución</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id_devolucion']; ?></td>
                        <td><?php echo $row['id_prestamo']; ?></td>
                        <td><?php echo $row['implemento']; ?></td>
                        <td><?php echo $row['fecha_devolucion']; ?></td>
                        <td><?php echo $row['estado']; ?></td>
                        <td><?php echo $row['observaciones'] ? $row['observaciones'] : 'Sin observaciones'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

