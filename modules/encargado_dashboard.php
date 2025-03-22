<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de Encargado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Encargado</title>
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
            <a class="navbar-brand" href="#">Panel Encargado</a>
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
            <h1 class="display-4">Bienvenido, <?php echo $_SESSION['rol']; ?>!</h1>
        </div>
        
        <!-- Tarjetas de opciones -->
        <div class="row justify-content-center g-4">
            <!-- Gestionar Usuarios -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-user-plus"></i> Gestionar Estudiantes</h5>
                        <p class="card-text">Registra y edita información de estudiantes.</p>
                        <a href="registrar_usuario_encargado.php" class="btn btn-primary mt-auto">Registrar Estudiante</a>
                        <a href="editar_usuario_encargado.php" class="btn btn-secondary mt-2">Editar Estudiante</a>
                    </div>
                </div>
            </div>
            <!-- Gestionar Implementos -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-tools"></i> Gestionar Implementos</h5>
                        <p class="card-text">Registra y actualiza los implementos disponibles.</p>
                        <a href="registrar_implemento_encargado.php" class="btn btn-primary mt-auto">Registrar Implemento</a>
                        <a href="editar_implemento_encargado.php" class="btn btn-secondary mt-2">Editar Implemento</a>
                    </div>
                </div>
            </div>
            <!-- Gestionar Préstamos -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-book"></i> Gestionar Préstamos</h5>
                        <p class="card-text">Realiza, cancela y consulta los préstamos.</p>
                        <a href="realizar_prestamo_encargado.php" class="btn btn-primary mt-auto">Realizar Préstamo</a>
                        <a href="cancelar_prestamo_encargado.php" class="btn btn-secondary mt-2">Cancelar Préstamo</a>
                    </div>
                </div>
            </div>
            <!-- Generar Reportes -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-chart-bar"></i> Generar Reportes</h5>
                        <p class="card-text">Crea reportes sobre préstamos y estudiantes.</p>
                        <a href="reporte_encargado.php" class="btn btn-primary mt-auto">Rep. Implementos </a>
                        <a href="reporte_usuario.php" class="btn btn-primary mt-auto">Rep. Estudiantes</a>
                        <a href="reporte_prestamos.php" class="btn btn-primary mt-auto">Rep. Prestamos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
