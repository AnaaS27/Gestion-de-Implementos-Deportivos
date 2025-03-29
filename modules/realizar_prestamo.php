<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Estudiante') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

$mensaje = ""; // Variable para mostrar mensajes en la misma página

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_implemento = $_POST['id_implemento'];
    $cantidad = intval($_POST['cantidad']);
    $horas_uso = intval($_POST['fecha_prestamo']);
    $observaciones_Est = $_POST['observaciones_Est'];

    $hora_prestamo = date("H:i:s");
    $hora_devolucion = date("H:i:s", strtotime("+$horas_uso hours", strtotime($hora_prestamo)));

    // Verificar la cantidad de préstamos activos del usuario
    $query_prestamos = "SELECT COUNT(*) as total FROM prestamo WHERE id_usuario = ? AND estado = 'activo'";
    $stmt = $conn->prepare($query_prestamos);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result_prestamos = $stmt->get_result();
    $row_prestamos = $result_prestamos->fetch_assoc();

    if ($row_prestamos['total'] >= 2) {
        $mensaje = "<div class='alert alert-danger'>No puedes solicitar más de dos implementos.</div>";
    } else {
        // Verificar disponibilidad del implemento
        $query = "SELECT cantidad FROM implemento WHERE id_implemento = ? AND estado = 'disponible'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_implemento);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cantidad_disponible = $row['cantidad'];

            if ($cantidad > 0 && $cantidad <= $cantidad_disponible) {
                // Restar la cantidad prestada en la base de datos
                $nueva_cantidad = $cantidad_disponible - $cantidad;
                $update_query = "UPDATE implemento SET cantidad = ? WHERE id_implemento = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ii", $nueva_cantidad, $id_implemento);
                $stmt->execute();

                // Insertar el préstamo en la base de datos
                $insert = "INSERT INTO prestamo (id_usuario, id_implemento, cantidad, fecha_prestamo, hora_prestamo, hora_devolucion, observaciones_Est, estado) 
                        VALUES (?, ?, ?, NOW(), ?, ?, ?, 'activo')";
                $stmt = $conn->prepare($insert);
                $stmt->bind_param("iiisss", $id_usuario, $id_implemento, $cantidad, $hora_prestamo, $hora_devolucion, $observaciones_Est);

                if ($stmt->execute()) {
                    $mensaje = "<div class='alert alert-success'>Préstamo realizado con éxito.</div>";
                } else {
                    $mensaje = "<div class='alert alert-danger'>Error al realizar el préstamo.</div>";
                }
            } else {
                $mensaje = "<div class='alert alert-warning'>Cantidad solicitada no disponible.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-warning'>El implemento no está disponible.</div>";
        }
    }
}

$implementos_query = "SELECT id_implemento, nombre, tipo, cantidad FROM implemento WHERE estado = 'disponible'";
$implementos_result = $conn->query($implementos_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Préstamo</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<div class="alert alert-info" role="alert">
    <h4 class="alert-heading">Reglas de Préstamo</h4>
    <ul>
        <li><strong>Máximo 2 implementos:</strong> Cada usuario solo puede solicitar hasta 2 implementos al mismo tiempo.</li>
        <li><strong>Tiempo límite de uso:</strong> El tiempo máximo permitido para el uso de un implemento es de 5 horas.</li>
        <li><strong>Uso exclusivo en la universidad:</strong> Los implementos solo pueden utilizarse dentro de las instalaciones de la universidad y no pueden llevarse a casa.</li>
    </ul>
</div>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Solicitud de Préstamo</h1>

        <?php if (!empty($mensaje)) echo $mensaje; ?> <!-- Aquí se muestra el mensaje -->

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Formulario de solicitud de Préstamo</h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="id_implemento" class="form-label">ID Implemento:</label>
                                <input type="number" class="form-control" id="id_implemento" name="id_implemento" placeholder="Ingrese el ID del implemento" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_prestamo" class="form-label">Horas de Uso:</label>
                                <input type="number" class="form-control" id="fecha_prestamo" name="fecha_prestamo" min="1" max="5" required>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones_Est" class="form-label">Observaciones:</label>
                                <textarea class="form-control" id="observaciones_Est" name="observaciones_Est" rows="3" placeholder="Añade comentarios o detalles adicionales"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Solicitar Préstamo</button>
                            </div>
                        </form>
                        <a href="estudiante_dashboard.php" class="btn btn-link mt-3 d-block text-center">Volver</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="mb-4">Implementos Disponibles</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Implemento</th>
                                <th>Tipo Implemento</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($implementos_result->num_rows > 0) {
                                while ($row = $implementos_result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['id_implemento']}</td>
                                        <td>{$row['nombre']}</td>
                                        <td>{$row['tipo']}</td>
                                        <td>{$row['cantidad']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No hay implementos disponibles.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
