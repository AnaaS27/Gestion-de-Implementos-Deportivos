<?php
session_start();

// Verificación de permisos
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

$mensaje = ""; // Variable para almacenar mensajes de éxito o error
$implemento = null;// Almacenará los datos del implemento encontrado

// Buscar implemento por ID
if (isset($_POST['buscar'])) {
    $id_implemento = mysqli_real_escape_string($conn, $_POST['id_implemento']);
    $result = mysqli_query($conn, "SELECT * FROM implemento WHERE id_implemento = '$id_implemento'");
    $implemento = mysqli_fetch_assoc($result);

    if (!$implemento) {
        $mensaje = "Implemento no encontrado.";
    }
}

// Actualizar implemento
if (isset($_POST['actualizar'])) {
    $id_implemento = mysqli_real_escape_string($conn, $_POST['id_implemento']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $cantidad = intval($_POST['cantidad']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    // Actualización en la base de datos
    $sql = "UPDATE implemento SET nombre = '$nombre', tipo = '$tipo', cantidad = $cantidad, estado = '$estado' WHERE id_implemento = '$id_implemento'";
    if (mysqli_query($conn, $sql)) {
        $mensaje = "Implemento actualizado con éxito.";
    } else {
        $mensaje = "Error al actualizar el implemento: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Implemento - Encargado</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Editar Implemento</h1>

        <!-- Mensaje de éxito o error -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?= strpos($mensaje, 'éxito') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <div class="card mx-auto mb-4" style="max-width: 600px;">
            <div class="card-body">
                <form method="POST" class="d-flex">
                    <input type="text" name="id_implemento" class="form-control me-2" placeholder="Ingrese ID de Implemento" required>
                    <button type="submit" name="buscar" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>

        <!-- Formulario para editar implemento -->
        <?php if ($implemento): ?>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <form method="POST">
                        <!-- ID Implemento (solo lectura) -->
                        <div class="mb-3">
                            <label for="id_implemento" class="form-label">ID Implemento</label>
                            <input type="text" id="id_implemento" name="id_implemento" class="form-control" value="<?= htmlspecialchars($implemento['id_implemento'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($implemento['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <input type="text" id="tipo" name="tipo" class="form-control" value="<?= htmlspecialchars($implemento['tipo'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" class="form-control" value="<?= htmlspecialchars($implemento['cantidad'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" id="estado" name="estado" class="form-control" value="<?= htmlspecialchars($implemento['estado'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <!-- Botón para actualizar -->
                        <button type="submit" name="actualizar" class="btn btn-success w-100">Actualizar Implemento</button>
                    </form>

                    <!-- Botón para regresar -->
                    <div class="text-center mt-3">
                        <a href="encargado_dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>