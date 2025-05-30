<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Asegúrate de que la conexión esté incluida

// Obtener los préstamos activos
$query = "SELECT p.id_prestamo, i.nombre AS implemento
          FROM prestamo p
          JOIN implemento i ON p.id_implemento = i.id_implemento
          WHERE p.estado = 'Activo'";


$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prestamo = $_POST['id_prestamo'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $estado = $_POST['estado'];
    $observaciones = $_POST['observaciones'];

    // Registrar la devolución
    $insert_query = "INSERT INTO devoluciones (id_prestamo, fecha_devolucion, estado, observaciones) 
                     VALUES ('$id_prestamo', '$fecha_devolucion', '$estado', '$observaciones')";
    
    if ($conn->query($insert_query) === TRUE) {
        header("Location: gestionar_devoluciones.php"); // Redirige después de registrar
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Devolución</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-5">
        <h2 class="text-center">Registrar Devolución</h2>
        
        <form method="POST" action="registrar_devolucion.php">
            <div class="mb-3">
                <label for="id_prestamo" class="form-label">Seleccionar Préstamo</label>
                <select id="id_prestamo" name="id_prestamo" class="form-select" required>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_prestamo']; ?>">
                            <?php echo $row['implemento']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_devolucion" class="form-label">Fecha de Devolución</label>
                <input type="date" id="fecha_devolucion" name="fecha_devolucion" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Implemento</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="Bueno">Bueno</option>
                    <option value="Regular">Regular</option>
                    <option value="Malo">Malo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Devolución</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
