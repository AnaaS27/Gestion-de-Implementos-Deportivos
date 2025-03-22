<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

// Manejo del formulario
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexion.php'; // Conexión a la base de datos

    $id_implemento = mysqli_real_escape_string($conn, $_POST['id_implemento']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $cantidad = intval($_POST['cantidad']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    $sql = "INSERT INTO implemento (id_implemento, nombre, tipo, cantidad, estado) 
            VALUES ('$id_implemento', '$nombre', '$tipo', $cantidad, '$estado')";

    if (mysqli_query($conn, $sql)) {
        $mensaje = "Implemento registrado con éxito.";
    } else {
        $mensaje = "Error al registrar el implemento: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Implemento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Registrar Implemento</h1>

        <!-- Mensaje de éxito o error -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?= strpos($mensaje, 'éxito') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <form method="POST">
                    <!-- ID Implemento -->
                    <div class="mb-3">
                        <label for="id_implemento" class="form-label">ID Implemento</label>
                        <input type="text" id="id_implemento" name="id_implemento" class="form-control" required>
                    </div>

                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <!-- Tipo -->
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" id="tipo" name="tipo" class="form-control" required>
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select" required>
                            <option value="disponible">Disponible</option>
                            <option value="no disponible">No Disponible</option>
                        </select>
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" class="btn btn-primary w-100">Registrar Implemento</button>
                </form>

                <!-- Botón para regresar -->
                <div class="text-center mt-3">
                    <a href="encargado_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Panel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>