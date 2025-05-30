<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php';

$mensaje = "";

// Verificar si se ha proporcionado un id_usuario para eliminar
if (isset($_GET['id_usuario'])) {
    $id_usuario = intval($_GET['id_usuario']);
    $sql = "DELETE FROM usuario WHERE id_usuario = $id_usuario";
    if (mysqli_query($conn, $sql)) {
        $mensaje = "Usuario eliminado con éxito.";
    } else {
        $mensaje = "Error al eliminar el usuario: " . mysqli_error($conn);
    }
}

// Obtener lista de usuarios
$result = mysqli_query($conn, "SELECT * FROM usuario");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Eliminar Usuario</h1>

        <!-- Mensaje de éxito o error -->
        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_usuario'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($row['rol'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="?id_usuario=<?= $row['id_usuario']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                       <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botón para regresar -->
        <div class="text-center mt-4">
            <a href="gestionar_usuarios.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>
