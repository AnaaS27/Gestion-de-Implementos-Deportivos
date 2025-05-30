<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = intval($_POST['id_usuario']); // ID del estudiante proporcionado
    $id_implemento = intval($_POST['id_implemento']);
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $observaciones = $_POST['observaciones'];

    // Verificar si el implemento existe y está disponible
    $query = "SELECT * FROM implemento WHERE id_implemento = ? AND estado = 'disponible'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_implemento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Registrar el préstamo
        $insert = "INSERT INTO prestamo (id_usuario, id_implemento, fecha_prestamo, fecha_devolucion, observaciones_Est) 
                   VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("iisss", $id_usuario, $id_implemento, $fecha_prestamo, $fecha_devolucion, $observaciones);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Préstamo registrado con éxito.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error al registrar el préstamo: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center'>El implemento no existe o no está disponible.</div>";
    }
}

// Consulta para obtener implementos disponibles
$implementos_query = "SELECT id_implemento, nombre, tipo, cantidad FROM implemento WHERE estado = 'disponible'";
$implementos_result = $conn->query($implementos_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar Préstamo</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Realizar Préstamo</h1>
        <div class="row">
            <!-- Formulario de Préstamo -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Formulario de Préstamo</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="id_usuario" class="form-label">ID Usuario (Estudiante):</label>
                                <input type="number" class="form-control" id="id_usuario" name="id_usuario" placeholder="Ingrese el ID del estudiante" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_implemento" class="form-label">ID Implemento:</label>
                                <input type="number" class="form-control" id="id_implemento" name="id_implemento" placeholder="Ingrese el ID del implemento" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_prestamo" class="form-label">Fecha de Préstamo:</label>
                                <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_devolucion" class="form-label">Fecha de Devolución:</label>
                                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones:</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Añade comentarios o detalles adicionales"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
                            </div>
                        </form>
                        <a href="encargado_dashboard.php" class="btn btn-link mt-3 d-block text-center">Volver</a>
                    </div>
                </div>
            </div>

            <!-- Tabla de Implementos Disponibles -->
            <div class="col-md-6">
                <h2 class="mb-4">Implementos Disponibles</h2>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($implementos_result && $implementos_result->num_rows > 0) {
                            while ($row = $implementos_result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['id_implemento']}</td>
                                    <td>{$row['nombre']}</td>
                                    <td>{$row['tipo']}</td>
                                    <td>{$row['cantidad']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay implementos disponibles.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
