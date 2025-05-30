<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

require '../conexion.php'; // Conexión a la base de datos

$mensaje = "";

// Eliminar implemento si se recibe el id_implemento
if (isset($_GET['id_implemento'])) {
    $id_implemento = $_GET['id_implemento'];
    $query = "DELETE FROM implemento WHERE id_implemento = $id_implemento";
    if (mysqli_query($conn, $query)) {
        $mensaje = "Implemento eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el implemento: " . mysqli_error($conn);
    }
}

// Paginar los resultados
$limit = 10;  // Número de implementos por página
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM implemento LIMIT $limit OFFSET $offset";
$resultado = mysqli_query($conn, $query);

// Obtener el total de registros para la paginación
$query_count = "SELECT COUNT(*) AS total FROM implemento";
$result_count = mysqli_query($conn, $query_count);
$total_implementos = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_implementos / $limit);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Implemento</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Eliminar Implemento</h1>

        <!-- Mensaje de error o éxito -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($implemento = mysqli_fetch_assoc($resultado)) { ?>
                            <tr>
                                <td><?php echo $implemento['id_implemento']; ?></td>
                                <td><?php echo $implemento['nombre']; ?></td>
                                <td><?php echo $implemento['tipo']; ?></td>
                                <td><?php echo $implemento['cantidad']; ?></td>
                                <td><?php echo $implemento['estado']; ?></td>
                                <td>
                                    <a href="eliminar_implemento.php?id_implemento=<?php echo $implemento['id_implemento']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este implemento?');">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <!-- Paginación -->
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="eliminar_implemento.php?page=<?php echo $page - 1; ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="eliminar_implemento.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="eliminar_implemento.php?page=<?php echo $page + 1; ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>

                <div class="text-center">
                    <a href="inventario.php" class="btn btn-secondary mt-3">
                        <i class="fas fa-arrow-left"></i> Volver al Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php mysqli_close($conn); ?>


