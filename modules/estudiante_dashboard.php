<?php
session_start();
// Verificar inicio de sesion del rol Estudiante
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Estudiante') {
    header("Location: ../login.php");
    exit;
}

require_once '../conexion.php'; // Archivo de conexión a la base de datos

// Obtener los préstamos del estudiante
$id_usuario = $_SESSION['id_usuario'];
$query = "
    SELECT p.id_prestamo, i.nombre AS implemento, p.fecha_prestamo, p.fecha_devolucion, p.estado, p.observaciones_Est, p.observaciones_Generales 
    FROM prestamo p 
    INNER JOIN implemento i ON p.id_implemento = i.id_implemento 
    WHERE p.id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Estudiante</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Encabezado -->
    <?php include '../includes/header.php'; ?>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Panel Estudiante</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Bienvenido, Estudiante</h1>
        </div>
        
        <!-- Tarjetas de opciones -->
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-book"></i> Solicitar Préstamo</h5>
                        <p class="card-text">Solicita implementos para tus actividades.</p>
                        <a href="realizar_prestamo.php" class="btn btn-primary mt-auto">Ir</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-undo"></i> Cancelar Préstamo</h5>
                        <p class="card-text">Revisa y cancela tus préstamos si es necesario.</p>
                        <a href="cancelar_prestamo.php" class="btn btn-primary mt-auto">Ir</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de préstamos -->
        <div class="mt-5">
            <h2 class="text-center">Tus Préstamos</h2>
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID Préstamo</th>
                        <th>Implemento</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                        <th>Observaciones Estudiante</th>
                        <th>Observaciones Generales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id_prestamo']; ?></td>
                                <td><?= $row['implemento']; ?></td>
                                <td><?= $row['fecha_prestamo']; ?></td>
                                <td><?= $row['fecha_devolucion']; ?></td>
                                <td><?= $row['estado']; ?></td>
                                <td><?= $row['observaciones_Est']; ?></td>
                                <td><?= $row['observaciones_Generales']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No tienes préstamos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pie de página -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>





