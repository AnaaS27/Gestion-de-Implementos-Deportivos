<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

require '../conexion.php'; // Conexión a la base de datos

// Definir la cantidad de registros por página
$registros_por_pagina = 10; // Cambia esto si deseas más o menos registros por página

// Determinar la página actual
if (isset($_GET['pagina'])) {
    $pagina_actual = $_GET['pagina'];
} else {
    $pagina_actual = 1;
}

// Calcular el inicio de los registros para la consulta SQL
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$query_total = "SELECT COUNT(*) as total FROM implemento"; // Asegúrate de que la tabla sea correcta
$resultado_total = mysqli_query($conn, $query_total);
$fila_total = mysqli_fetch_assoc($resultado_total);
$total_registros = $fila_total['total'];

// Calcular el número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener los implementos de la página actual
$query = "SELECT * FROM implemento LIMIT $inicio, $registros_por_pagina";
$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Gestionar Inventario</h1>
        <p class="text-center">Bienvenido, <?php echo $_SESSION['rol']; ?>. Aquí puedes gestionar los implementos del inventario.</p>

        <!-- Opciones para gestionar implementos -->
        <div class="list-group mb-4">
            <a href="registrar_implemento.php" class="list-group-item list-group-item-action">
                <i class="fas fa-plus-circle"></i> Registrar Implemento
            </a>
            <a href="editar_implemento.php" class="list-group-item list-group-item-action">
                <i class="fas fa-edit"></i> Editar Implemento
            </a>
            <a href="eliminar_implemento.php" class="list-group-item list-group-item-action">
                <i class="fas fa-trash-alt"></i> Eliminar Implemento
            </a>
        </div>

        <!-- Tabla de implementos registrados -->
        <div class="card">
            <div class="card-body">
                <h3>Implementos Registrados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
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
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($pagina_actual == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=1" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </a>
                </li>
                <li class="page-item <?php if ($pagina_actual == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                    <li class="page-item <?php if ($pagina_actual == $i) echo 'active'; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php if ($pagina_actual == $total_paginas) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <li class="page-item <?php if ($pagina_actual == $total_paginas) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=<?php echo $total_paginas; ?>" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Botón para regresar al panel anterior -->
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-left"></i> Regresar al Panel
            </a>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>

<?php mysqli_close($conn); ?>



