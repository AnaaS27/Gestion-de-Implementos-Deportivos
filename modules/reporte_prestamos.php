<?php
session_start();

// Verificar si el usuario está autenticado y es encargado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

// Obtener los préstamos
$prestamos = [];
$sql = "SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion, p.estado, p.observaciones_Est, p.observaciones_Generales,
               e.nombre AS estudiante, i.nombre AS implemento
        FROM prestamo p
        JOIN estudiante e ON p.id_usuario = e.id_usuario
        JOIN implemento i ON p.id_implemento = i.id_implemento";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $prestamos[] = $row;
    }
} else {
    $error = "Error al obtener los préstamos: " . mysqli_error($conn);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Préstamos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Reporte de Préstamos</h1>

        <!-- Mensaje de error si ocurre algún problema -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de préstamos -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Préstamo</th>
                            <th>Estudiante</th>
                            <th>Implemento</th>
                            <th>Fecha de Préstamo</th>
                            <th>Fecha de Devolución</th>
                            <th>Estado</th>
                            <th>Observaciones Estudiante</th>
                            <th>Observaciones Generales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prestamos)): ?>
                            <?php foreach ($prestamos as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['id_prestamo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['estudiante'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['implemento'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_prestamo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_devolucion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['observaciones_Est'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($prestamo['observaciones_Generales'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay préstamos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón para volver -->
        <div class="text-center mt-4">
            <a href="encargado_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>


