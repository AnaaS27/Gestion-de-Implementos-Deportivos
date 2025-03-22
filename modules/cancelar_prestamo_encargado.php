<?php
session_start();
// Verificar si el usuario tiene rol "Encargado" y está autenticado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php';// Conexión a la base de datos

$prestamos = []; // Arreglo para almacenar los préstamos activos

// Obtener todos los préstamos activos
$query = "SELECT id_prestamo, id_usuario, id_implemento, fecha_prestamo, fecha_devolucion, estado FROM prestamo WHERE estado != 'Cancelado'";
$result = $conn->query($query);

// Verificar si existen préstamos activos
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prestamos[] = $row;
    }
}

// Manejo del formulario cuando se envía una solicitud POST para cancelar un préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prestamo = $_POST['id_prestamo']; // ID del préstamo a cancelar
    $estado = 'Cancelado'; // Estado a actualizar
    $observaciones = $_POST['observaciones_Est']; // Observaciones del encargado

    // Verificar si el préstamo existe
    $query = "SELECT * FROM prestamo WHERE id_prestamo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_prestamo); // Evitar SQL Injection
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
        $message = "El préstamo no existe.";// Mensaje si el préstamo no se encuentra
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cancelar Préstamo - Encargado</title>
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
        <!-- Título de la página -->
        <h1 class="text-center mb-4">Cancelar Préstamo</h1>

        <!-- Mostrar mensajes -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Listado de Préstamos -->
            <div class="col-md-10 mx-auto">
                <h3 class="mb-3">Préstamos Activos</h3>
                <?php if (!empty($prestamos)): ?>
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Préstamo</th>
                                <th>ID Usuario</th>
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
                                    <td><?= $prestamo['id_usuario'] ?></td>
                                    <td><?= $prestamo['id_implemento'] ?></td>
                                    <td><?= $prestamo['fecha_prestamo'] ?></td>
                                    <td><?= $prestamo['fecha_devolucion'] ?></td>
                                    <td><?= $prestamo['estado'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">No hay préstamos activos.</p>
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
                            <!-- Selección del ID del préstamo -->
                            <div class="mb-3">
                                <label for="id_prestamo" class="form-label">ID Préstamo:</label>
                                <select class="form-select" id="id_prestamo" name="id_prestamo" required>
                                    <option value="" disabled selected>Seleccione un préstamo</option>
                                    <?php foreach ($prestamos as $prestamo): ?>
                                        <option value="<?= $prestamo['id_prestamo'] ?>">
                                            Préstamo #<?= $prestamo['id_prestamo'] ?> - Usuario #<?= $prestamo['id_usuario'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Campo de observaciones -->
                            <div class="mb-3">
                                <label for="observaciones_Est" class="form-label">Observaciones:</label>
                                <textarea class="form-control" id="observaciones_Est" name="observaciones_Est" rows="3" placeholder="Añade comentarios o razones"></textarea>
                            </div>
                            <!-- Botón de enviar -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">Cancelar Préstamo</button>
                            </div>
                        </form>
                        <!-- Enlace de volver -->
                        <a href="encargado_dashboard.php" class="btn btn-link mt-3 d-block text-center">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pie de página -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>