<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de Administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php"); // Redirige al inicio de sesión si no es administrador
    exit; // Detiene la ejecución del script
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8"> <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Diseño responsivo -->
    <title>Panel de Administración</title> <!-- Título de la página -->
    <!-- Enlace al archivo de estilos personalizado -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Integración de Bootstrap CSS desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Integración de Bootstrap JS desde un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Integración de Font Awesome para íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Encabezado -->
    <?php include '../includes/header.php'; ?>
    <!-- Se incluye un archivo externo que contiene el encabezado común para las páginas -->

    <!-- Barra de navegación principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Logo y enlace del panel de administración -->
            <a class="navbar-brand" href="#">Panel Admin</a>
            <!-- Botón para colapsar la barra de navegación en dispositivos pequeños -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Contenido colapsable de la barra de navegación -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Enlace para cerrar sesión -->
                    <li class="nav-item">
                        <a class="nav-link active" href="../logout.php">Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container my-5">
        <!-- Mensaje de bienvenida centrado -->
        <div class="text-center mb-4">
            <h1 class="display-4">Bienvenido, <?php echo $_SESSION['rol']; ?>!</h1>
        </div>
        
        <!-- Fila que contiene las tarjetas de opciones -->
        <div class="row justify-content-center g-4">
            <!-- Tarjeta 1: Gestión de préstamos -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Título y descripción de la tarjeta -->
                        <h5 class="card-title"><i class="fas fa-book"></i> Gestionar Préstamos</h5>
                        <p class="card-text">Consulta y administra los préstamos realizados.</p>
                        <!-- Botón de acceso -->
                        <a href="gestionar_prestamos.php" class="btn mt-auto">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Gestión de usuarios -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Título y descripción de la tarjeta -->
                        <h5 class="card-title"><i class="fas fa-users"></i> Gestionar Usuarios</h5>
                        <p class="card-text">Agrega, edita o elimina usuarios del sistema.</p>
                        <!-- Botón de acceso -->
                        <a href="gestionar_usuarios.php" class="btn mt-auto">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Gestión de implementos -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <!-- Título y descripción de la tarjeta -->
                        <h5 class="card-title"><i class="fas fa-clipboard-list"></i> Gestionar Implementos</h5>
                        <p class="card-text">Consulta y actualiza el inventario disponible.</p>
                        <!-- Botón de acceso -->
                        <a href="inventario.php" class="btn mt-auto">Ir</a>
                    </div>
                </div>
            </div>

            <!-- Nueva tarjeta: Gestión de Devoluciones -->
            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><i class="fas fa-undo"></i> Gestionar Devoluciones</h5>
                        <p class="card-text">Registra y administra las devoluciones de implementos.</p>
                        <a href="gestionar_devoluciones.php" class="btn mt-auto">Ir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
