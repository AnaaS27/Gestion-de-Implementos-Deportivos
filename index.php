<?php
session_start(); // Inicia la sesión

// Mensaje de bienvenida
$mensaje_bienvenida = "¡Bienvenidos a la gestión de implementos deportivos de la Universidad del Valle!";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión de Implementos Deportivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> <!-- Archivo de estilos externo -->
</head>
<body class="bg-light">

    <!-- Header -->
    <header>
        <img src="img/logo_univalle.jpg" alt="Logo Universidad del Valle">
        <h1 class="mt-3">Gestión de Implementos Deportivos</h1>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="text-center">
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <div class="alert alert-success" role="alert">
                    <h2 class="mb-3">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['rol']); ?>!</h2>
                </div>
                <?php if ($_SESSION['rol'] == 'Administrador'): ?>
                    <a href="modules/admin_dashboard.php" class="btn btn-primary btn-lg my-2"><i class="fas fa-user-shield"></i> Ir al Panel de Administrador</a>
                <?php elseif ($_SESSION['rol'] == 'Estudiante'): ?>
                    <a href="modules/estudiante_dashboard.php" class="btn btn-success btn-lg my-2"><i class="fas fa-user-graduate"></i> Ir al Panel de Estudiante</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-danger btn-lg my-2"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <h2 class="mb-3"><?php echo $mensaje_bienvenida; ?></h2>
                </div>
                <a href="login.php" class="btn btn-primary btn-lg my-2"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>






