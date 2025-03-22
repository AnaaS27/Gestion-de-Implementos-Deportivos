<?php
session_start();
// Verificación de permisos
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

// Incluir el archivo de conexión a la base de datos
require_once '../conexion.php';

// Verificar si se recibe el ID del préstamo
if (!isset($_GET['id'])) {
    echo "ID del préstamo no proporcionado.";
    exit;
}

// Obtener el ID del préstamo y asegurarse de que sea un valor entero
$id_prestamo = intval($_GET['id']);

// Obtener los datos actuales del préstamo
$query = "SELECT * FROM prestamo WHERE id_prestamo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_prestamo);
$stmt->execute();
$result = $stmt->get_result();
$prestamo = $result->fetch_assoc();

// Verificar si se encontró el préstamo
if (!$prestamo) {
    echo "Préstamo no encontrado.";
    exit;
}

// Obtener datos de implementos para la lista desplegable
$implementos = $conn->query("SELECT id_implemento, nombre FROM implemento");

// Actualizar los datos del préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_implemento = intval($_POST['id_implemento']);
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $estado = $_POST['estado'];
    $observaciones_Est = $_POST['observaciones_Est'];
    $observaciones_Generales = $_POST['observaciones_Generales'];

    // Validar que el ID de implemento exista
    $implemento_query = "SELECT id_implemento FROM implemento WHERE id_implemento = ?";
    $implemento_stmt = $conn->prepare($implemento_query);
    $implemento_stmt->bind_param("i", $id_implemento);
    $implemento_stmt->execute();
    $implemento_result = $implemento_stmt->get_result();

    // Si el implemento no existe, mostrar un error y detener la ejecución
    if ($implemento_result->num_rows == 0) {
        echo "Error: El ID de implemento no existe.";
        exit;
    }

    // Actualizar el préstamo
    $update_query = "
        UPDATE prestamo 
        SET id_implemento = ?, fecha_prestamo = ?, fecha_devolucion = ?, estado = ?, observaciones_Est = ?, observaciones_Generales = ?
        WHERE id_prestamo = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("isssssi", $id_implemento, $fecha_prestamo, $fecha_devolucion, $estado, $observaciones_Est, $observaciones_Generales, $id_prestamo);

    // Ejecutar la actualización y redirigir si es exitosa
    if ($update_stmt->execute()) {
        header("Location: gestionar_prestamos.php?mensaje=Préstamo actualizado correctamente");
        exit;
    } else {
        echo "Error al actualizar el préstamo: " . $update_stmt->error;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Préstamo</title>
    <!-- Enlazar Bootstrap para el diseño de la página -->
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Incluir el encabezado del sitio -->
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Préstamo</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <!-- ID Usuario -->
                    <div class="mb-3">
                        <label for="id_usuario" class="form-label">ID Usuario:</label>
                        <input type="text" class="form-control" id="id_usuario" name="id_usuario" value="<?= $prestamo['id_usuario']; ?>" readonly>
                    </div>

                    <!-- Implemento -->
                    <div class="mb-3">
                        <label for="id_implemento" class="form-label">Implemento:</label>
                        <select class="form-select" id="id_implemento" name="id_implemento" required>
                            <?php while ($implemento = $implementos->fetch_assoc()): ?>
                                <option value="<?= $implemento['id_implemento']; ?>" 
                                    <?= ($implemento['id_implemento'] == $prestamo['id_implemento']) ? 'selected' : ''; ?>>
                                    <?= $implemento['nombre']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Fechas -->
                    <div class="mb-3">
                        <label for="fecha_prestamo" class="form-label">Fecha Préstamo:</label>
                        <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" value="<?= $prestamo['fecha_prestamo']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_devolucion" class="form-label">Fecha Devolución:</label>
                        <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" value="<?= $prestamo['fecha_devolucion']; ?>" required>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado:</label>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="estadoDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $prestamo['estado']; ?>
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="estadoDropdown">
                                <li><button class="dropdown-item" type="button" onclick="setEstado('Pendiente')">Pendiente</button></li>
                                <li><button class="dropdown-item" type="button" onclick="setEstado('Aceptado')">Aceptado</button></li>
                                <li><button class="dropdown-item" type="button" onclick="setEstado('Cancelado')">Cancelado</button></li>
                            </ul>
                        </div>
                        <input type="hidden" id="estado" name="estado" value="<?= $prestamo['estado']; ?>">
                    </div>

                    <!-- Observaciones Estudiantes -->
                    <div class="mb-3">
                        <label for="observaciones_Est" class="form-label">Observaciones:</label>
                        <textarea class="form-control" id="observaciones_Est" name="observaciones_Est" rows="3"><?= htmlspecialchars($prestamo['observaciones_Est'] ?? '', ENT_QUOTES); ?></textarea>
                    </div>

                    <!-- Observaciones Generales -->
                    <div class="mb-3">
                        <label for="observaciones_Generales" class="form-label">Observaciones Generales:</label>
                        <textarea class="form-control" id="observaciones_Generales" name="observaciones_Generales" rows="3"><?= htmlspecialchars($prestamo['observaciones_Generales'] ?? '', ENT_QUOTES); ?></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="gestionar_prestamos.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Incluir el pie de página -->
    <?php include '../includes/footer.php'; ?>

    <!-- Script para actualizar el estado seleccionado -->
    <script>
        function setEstado(estado) {
            document.getElementById('estado').value = estado;
            document.getElementById('estadoDropdown').innerText = estado;
        }
    </script>

</body>
</html>


