<?php
session_start();

// Verificar si el usuario está autenticado y es encargado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php"); 
    exit;
}

$mensaje = "";

// Manejo del formulario de agregar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexion.php'; // Conexión a la base de datos

    $tipo = $_POST['tipo'];

    if ($tipo == 'Estudiante') {
        // Procesar formulario de estudiante
        $id_curso = intval($_POST['id_curso']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $correo_institucional = mysqli_real_escape_string($conn, $_POST['correo_institucional']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insertar en la tabla `usuario`
        $usuario_query = "INSERT INTO usuario (correo, password, rol) VALUES ('$correo_institucional', '$password', 'Estudiante')";
        if (mysqli_query($conn, $usuario_query)) {
            $id_usuario = mysqli_insert_id($conn);

            // Insertar en la tabla `estudiante`
            $estudiante_query = "INSERT INTO estudiante (id_usuario, id_curso, nombre, correo_institucional, telefono) VALUES ($id_usuario, $id_curso, '$nombre', '$correo_institucional', '$telefono')";
            if (mysqli_query($conn, $estudiante_query)) {
                $mensaje = "Estudiante agregado con éxito.";
            } else {
                $mensaje = "Error al agregar estudiante: " . mysqli_error($conn);
            }
        } else {
            $mensaje = "Error al agregar usuario: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Registrar Estudiante</h1>

        <!-- Mensaje de resultado -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?= strpos($mensaje, 'éxito') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <!-- Formulario de Estudiante -->
                <form method="POST">
                    <input type="hidden" name="tipo" value="Estudiante">
                    <div class="mb-3">
                        <label for="id_curso" class="form-label">ID del Curso</label>
                        <input type="number" id="id_curso" name="id_curso" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombreEstudiante" class="form-label">Nombre</label>
                        <input type="text" id="nombreEstudiante" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="correoInstitucional" class="form-label">Correo Institucional</label>
                        <input type="email" id="correoInstitucional" name="correo_institucional" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoEstudiante" class="form-label">Teléfono</label>
                        <input type="text" id="telefonoEstudiante" name="telefono" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" value="" placeholder="Deja vacío si no deseas cambiarla">
                            <button type="button" class="toggle-password-btn" id="togglePassword">
                                <span id="iconoPassword">👁️</span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Agregar Estudiante</button>
                </form>
                <!-- Botón para regresar -->
                <div class="text-center mt-3">
                    <a href="encargado_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('iconoPassword');

        // Cambiar el tipo de input
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.textContent = '🙈'; // Cambia el ícono (opcional)
        } else {
            passwordField.type = 'password';
            icon.textContent = '👁️'; // Cambia el ícono (opcional)
        }
    });
</script>

</body>
</html>
