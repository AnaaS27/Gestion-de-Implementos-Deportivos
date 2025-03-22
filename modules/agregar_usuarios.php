<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

$mensaje = "";

// Manejo del formulario de agregar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../conexion.php';

    $tipo = $_POST['tipo'];

    if ($tipo == 'Estudiante') {
        // Procesar formulario de estudiante
        $id_curso = intval($_POST['id_curso']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $correo_institucional = mysqli_real_escape_string($conn, $_POST['correo_institucional']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

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
    } elseif ($tipo == 'Encargado') {
        // Procesar formulario de encargado
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $correo = mysqli_real_escape_string($conn, $_POST['correo']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

        // Insertar en la tabla `usuario`
        $usuario_query = "INSERT INTO usuario (correo, password, rol) VALUES ('$correo', '$password', 'Encargado')";
        if (mysqli_query($conn, $usuario_query)) {
            $id_usuario = mysqli_insert_id($conn);

            // Insertar en la tabla `encargado`
            $encargado_query = "INSERT INTO encargado (id_usuario, nombre, correo, telefono) VALUES ($id_usuario, '$nombre', '$correo', '$telefono')";
            if (mysqli_query($conn, $encargado_query)) {
                $mensaje = "Encargado agregado con éxito.";
            } else {
                $mensaje = "Error al agregar encargado: " . mysqli_error($conn);
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
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Agregar Usuario</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <!-- Menú para seleccionar tipo de usuario -->
                <div class="mb-3">
                    <label for="tipoUsuario" class="form-label">Selecciona el tipo de usuario</label>
                    <select id="tipoUsuario" class="form-select" onchange="mostrarFormulario()">
                        <option value="">Selecciona</option>
                        <option value="Encargado">Encargado</option>
                        <option value="Estudiante">Estudiante</option>
                    </select>
                </div>

                <!-- Formulario para agregar estudiante -->
                <form id="formEstudiante" method="POST" style="display: none;">
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
                            <input type="password" id="passwordEstudiante" name="password" class="form-control"
                                value="<?= htmlspecialchars($datos_adicionales['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            <button type="button" class="toggle-password-btn" id="togglePasswordEstudiante">
                                <span id="iconoPasswordEstudiante">👁️</span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Agregar Estudiante</button>
                </form>

                <!-- Formulario para agregar encargado -->
                <form id="formEncargado" method="POST" style="display: none;">
                    <input type="hidden" name="tipo" value="Encargado">
                    <div class="mb-3">
                        <label for="nombreEncargado" class="form-label">Nombre</label>
                        <input type="text" id="nombreEncargado" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="correoEncargado" class="form-label">Correo</label>
                        <input type="email" id="correoEncargado" name="correo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoEncargado" class="form-label">Teléfono</label>
                        <input type="text" id="telefonoEncargado" name="telefono" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" id="passwordEncargado" name="password" class="form-control"
                                value="<?= htmlspecialchars($datos_adicionales['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            <button type="button" class="toggle-password-btn" id="togglePasswordEncargado">
                                <span id="iconoPasswordEncargado">👁️</span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Agregar Encargado</button>
                </form>
            </div>
        </div>
    <div class="text-center mt-4">
        <a href="gestionar_usuarios.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function mostrarFormulario() {
            const tipoUsuario = document.getElementById('tipoUsuario').value;
            document.getElementById('formEstudiante').style.display = (tipoUsuario === 'Estudiante') ? 'block' : 'none';
            document.getElementById('formEncargado').style.display = (tipoUsuario === 'Encargado') ? 'block' : 'none';
        }
    </script>

<script>
    document.getElementById('togglePasswordEstudiante').addEventListener('click', function () {
        const passwordField = document.getElementById('passwordEstudiante');
        const icon = document.getElementById('iconoPasswordEstudiante');
        togglePasswordVisibility(passwordField, icon);
    });

    document.getElementById('togglePasswordEncargado').addEventListener('click', function () {
        const passwordField = document.getElementById('passwordEncargado');
        const icon = document.getElementById('iconoPasswordEncargado');
        togglePasswordVisibility(passwordField, icon);
    });

    function togglePasswordVisibility(passwordField, icon) {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.textContent = '🙈';
        } else {
            passwordField.type = 'password';
            icon.textContent = '👁️';
        }
    }
</script>

</body>
</html>






