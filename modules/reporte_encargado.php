<?php
session_start();

// Verificar si el usuario está autenticado y es encargado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

// Obtener los implementos y sus estados
$implementos = [];
$sql = "SELECT id_implemento, nombre, tipo, cantidad, estado FROM implemento";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $implementos[] = $row;
    }
} else {
    $error = "Error al obtener los implementos: " . mysqli_error($conn);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Estados de Implementos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Reporte de Estados de Implementos</h1>

        <!-- Mensaje de error si ocurre algún problema -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de implementos -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Implemento</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($implementos)): ?>
                            <?php foreach ($implementos as $implemento): ?>
                                <tr>
                                    <td><?= htmlspecialchars($implemento['id_implemento'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($implemento['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($implemento['tipo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= intval($implemento['cantidad']); ?></td>
                                    <td><?= htmlspecialchars($implemento['estado'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay implementos registrados.</td>
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