<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Estudiante') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_implemento = $_POST['id_implemento'];
    $cantidad = $_POST['cantidad'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $observaciones_Est = $_POST['observaciones_Est'];


    $query = "SELECT cantidad FROM implemento WHERE id_implemento = ? AND estado = 'disponible'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_implemento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cantidad_disponible = $row['cantidad'];

        if ($cantidad > 0 && $cantidad <= $cantidad_disponible) {
            //Insertar el prestamo
            $insert = "INSERT INTO prestamo (id_usuario, id_implemento, cantidad, fecha_prestamo, observaciones_Est)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("iiiss", $id_usuario, $id_implemento, $cantidad, $fecha_prestamo, $observaciones_Est);
            
            if ($stmt->execute()) {
                // Actualizar la cantidad disponible
                $nueva_cantidad = $cantidad_disponible - $cantidad;
                $update = "UPDATE implemento SET cantidad = ? WHERE id_implemento = ?";
                $stmt = $conn->prepare($update);
                $stmt->bind_param("ii", $nueva_cantidad, $id_implemento);
                $stmt->execute();

                echo "Préstamo solicitado con éxito.";
            } else {
                echo "Error al solicitar el préstamo.";
            }
        } else {
            echo "Cantidad solicitada no disponible.";
        }
    } else {
        echo "El implemento no existe o no esta disponible.";
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
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Solicitud de Préstamo</h1>
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
                                <label for="fecha_prestamo" class="form-label">Fecha de Préstamo:</label>
                                <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" required>
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
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar implemento por nombre...">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="implementosTable">
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
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#implementosTable tbody tr');
            
            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                row.style.display = name.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
