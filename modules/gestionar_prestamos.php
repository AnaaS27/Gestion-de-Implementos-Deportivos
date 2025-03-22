<?php
session_start();
// Verifica si el usuario está autenticado y tiene el rol de "Administrador" o "Encargado".
// Si no, redirige al login.
if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] != 'Administrador' && $_SESSION['rol'] != 'Encargado')) {
    header("Location: ../login.php");
    exit;
}

require_once '../conexion.php'; // Archivo de conexión a la base de datos

// Verificar si se recibió una solicitud de eliminación
if (isset($_GET['eliminar'])) {
    $id_prestamo = intval($_GET['eliminar']);

    // Validar si el préstamo existe
    $query = "SELECT * FROM prestamo WHERE id_prestamo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_prestamo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Eliminar el préstamo
        $delete_query = "DELETE FROM prestamo WHERE id_prestamo = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $id_prestamo);

        if ($delete_stmt->execute()) {
            $mensaje = "Préstamo eliminado correctamente.";
        } else {
            $error = "Error al eliminar el préstamo: " . $delete_stmt->error;
        }
    } else {
        $error = "El préstamo no existe.";
    }
}


// Obtener todos los préstamos
$query = "SELECT id_prestamo, id_usuario, fecha_prestamo, fecha_devolucion, estado, observaciones_Est, observaciones_Generales FROM prestamo";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Gestionar Préstamos</h1>

        <!-- Mensaje de alerta si no hay préstamos -->
        <?php if ($result->num_rows == 0): ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay préstamos registrados.
            </div>
        <?php endif; ?>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success text-center" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>


        <!-- Tabla de préstamos -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Préstamo</th>
                    <th>ID Usuario</th>
                    <th>Fecha Préstamo</th>
                    <th>Fecha Devolución</th>
                    <th>Estado</th>
                    <th>Observaciones Estudiante</th>
                    <th>Observaciones Generales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_prestamo']; ?></td>
                            <td><?php echo $row['id_usuario']; ?></td>
                            <td><?php echo $row['fecha_prestamo']; ?></td>
                            <td><?php echo $row['fecha_devolucion']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['observaciones_Est']; ?></td>
                            <td><?php echo $row['observaciones_Generales']; ?></td>
                            <td>
                                <a href="editar_prestamo.php?id=<?php echo $row['id_prestamo']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="gestionar_prestamos.php?eliminar=<?php echo $row['id_prestamo']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de eliminar este préstamo?');">
                                   <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-primary btn-lg"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>

