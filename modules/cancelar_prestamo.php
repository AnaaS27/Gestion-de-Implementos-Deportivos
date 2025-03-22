<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de "Estudiante"
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Estudiante') {
    header("Location: ../login.php"); // Redirigir al login si no cumple con los requisitos
    exit;
}

include '../conexion.php'; // Incluir el archivo de conexión a la base de datos

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario de la sesión
$prestamos = []; // Arreglo para almacenar los préstamos activos del usuario


// Obtener los préstamos activos del usuario
$query = "SELECT id_prestamo, id_implemento, fecha_prestamo, fecha_devolucion, estado FROM prestamo WHERE id_usuario = ? AND estado != 'Cancelado'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Guardar los préstamos activos en un arreglo
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prestamos[] = $row;
    }
}

// Procesamiento del formulario al cancelar un préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prestamo = $_POST['id_prestamo']; // ID del préstamo seleccionado
    $estado = 'Cancelado'; // Nuevo estado
    $observaciones = $_POST['observaciones_Est']; // Observaciones del estudiante

    // Verificar si el préstamo existe y pertenece al usuario
    $query = "SELECT * FROM prestamo WHERE id_prestamo = ? AND id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_prestamo, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Actualizar el estado del préstamo a Cancelado y añadir observaciones
        $update = "UPDATE prestamo SET estado = ?, observaciones_Est = ? WHERE id_prestamo = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ssi", $estado, $observaciones, $id_prestamo);
        if ($stmt->execute()) {
            $message = "Préstamo cancelado con éxito."; // Mensaje de éxito
            header("Refresh:2"); // Refrescar la página para mostrar los datos actualizados
        } else {
            $message = "Error al cancelar el préstamo."; // Mensaje de error
        }
    } else {
        $message = "El préstamo no existe o no pertenece a este usuario."; // Mensaje de validación
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cancelar Préstamo</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Encabezado -->
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <!-- Título principal -->
        <h1 class="text-center mb-4">Cancelar Préstamo</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Listado de Préstamos -->
            <div class="col-md-8 mx-auto">
                <h3 class="mb-3">Mis Préstamos Activos</h3>
                <?php if (!empty($prestamos)): ?>
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Préstamo</th>
                                <th>ID Implemento</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prestamos as $prestamo): ?>
                                <tr>
                                    <td><?= $prestamo['id_prestamo'] ?></td>
                                    <td><?= $prestamo['id_implemento'] ?></td>
                                    <td><?= $prestamo['fecha_prestamo'] ?></td>
                                    <td><?= $prestamo['fecha_devolucion'] ?></td>
                                    <td><?= $prestamo['estado'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No tienes préstamos activos.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulario para Cancelar Préstamo -->
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Cancelar un Préstamo</h5>
                        <form method="POST">
                            <!-- Selección del préstamo -->
                            <div class="mb-3">
                                <label for="id_prestamo" class="form-label">ID Préstamo:</label>
                                <select class="form-select" id="id_prestamo" name="id_prestamo" required>
                                    <option value="" disabled selected>Seleccione un préstamo</option>
                                    <?php foreach ($prestamos as $prestamo): ?>
                                        <option value="<?= $prestamo['id_prestamo'] ?>">
                                            Préstamo #<?= $prestamo['id_prestamo'] ?> - Implemento #<?= $prestamo['id_implemento'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Observaciones -->
                            <div class="mb-3">
                                <label for="observaciones_Est" class="form-label">Observaciones:</label>
                                <textarea class="form-control" id="observaciones_Est" name="observaciones_Est" rows="3" placeholder="Añade comentarios o razones"></textarea>
                            </div>
                            <!-- Botón para cancelar el préstamo -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">Cancelar Préstamo</button>
                            </div>
                        </form>
                        <!-- Enlace de regreso -->
                        <a href="estudiante_dashboard.php" class="btn btn-link mt-3 d-block text-center">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pie de página -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>




