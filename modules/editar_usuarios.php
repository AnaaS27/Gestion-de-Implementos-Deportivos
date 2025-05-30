<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

$mensaje = "";
$usuario = [];
$detalle = [];

// Manejo del formulario de selección de usuario
if (isset($_POST['seleccionar_usuario'])) {
    $tipo = $_POST['tipo'];
    $id_usuario = intval($_POST['id_usuario']);

    // Consultar datos del usuario seleccionado
    $query_usuario = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
    $result_usuario = mysqli_query($conn, $query_usuario);

    if ($result_usuario && mysqli_num_rows($result_usuario) > 0) {
        $usuario = mysqli_fetch_assoc($result_usuario);

        if ($tipo == 'Estudiante') {
            $query_detalle = "SELECT * FROM estudiante WHERE id_usuario = $id_usuario";
        } elseif ($tipo == 'Encargado') {
            $query_detalle = "SELECT * FROM encargado WHERE id_usuario = $id_usuario";
        } else {
            $mensaje = "Tipo de usuario desconocido.";
        }

        $result_detalle = mysqli_query($conn, $query_detalle);
        $detalle = ($result_detalle && mysqli_num_rows($result_detalle) > 0) ? mysqli_fetch_assoc($result_detalle) : [];
    } else {
        $mensaje = "Usuario no encontrado.";
    }
}

// Manejo del formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_usuario'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Actualizar tabla `usuario`
    $actualizar_usuario = "UPDATE usuario SET correo = '$correo'";
    if ($password) {
        $actualizar_usuario .= ", password = '$password'";
    }
    $actualizar_usuario .= " WHERE id_usuario = $id_usuario";

    if (mysqli_query($conn, $actualizar_usuario)) {
        $tipo = $_POST['tipo'];
        if ($tipo == 'Estudiante') {
            $id_curso = intval($_POST['id_curso']);
            $actualizar_detalle = "UPDATE estudiante SET nombre = '$nombre', correo_institucional = '$correo', telefono = '$telefono', id_curso = $id_curso WHERE id_usuario = $id_usuario";
        } elseif ($tipo == 'Encargado') {
            $actualizar_detalle = "UPDATE encargado SET nombre = '$nombre', correo = '$correo', telefono = '$telefono' WHERE id_usuario = $id_usuario";
        }

        if (mysqli_query($conn, $actualizar_detalle)) {
            $mensaje = "Usuario actualizado con éxito.";
        } else {
            $mensaje = "Error al actualizar detalles: " . mysqli_error($conn);
        }
    } else {
        $mensaje = "Error al actualizar usuario: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Editar Usuario</h1>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Selección del tipo de usuario -->
        <form method="POST" class="mb-5">
            <div class="mb-3">
                <label for="tipo" class="form-label">Selecciona el tipo de usuario</label>
                <select id="tipo" name="tipo" class="form-select" required>
                    <option value="">Selecciona</option>
                    <option value="Estudiante">Estudiante</option>
                    <option value="Encargado">Encargado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_usuario" class="form-label">ID del Usuario</label>
                <input type="number" id="id_usuario" name="id_usuario" class="form-control" required>
            </div>
            <button type="submit" name="seleccionar_usuario" class="btn btn-primary w-100">Cargar Usuario</button>
        </form>

        <!-- Formulario de edición -->
        <?php if (!empty($usuario) && !empty($detalle)): ?>
            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario']; ?>">
                        <input type="hidden" name="tipo" value="<?= htmlspecialchars($_POST['tipo'], ENT_QUOTES, 'UTF-8'); ?>">

                        <?php if ($_POST['tipo'] == 'Estudiante'): ?>
                            <div class="mb-3">
                                <label for="id_curso" class="form-label">ID del Curso</label>
                                <input type="number" id="id_curso" name="id_curso" class="form-control" value="<?= $detalle['id_curso']; ?>" required>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $detalle['nombre']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" id="correo" name="correo" class="form-control" value="<?= $detalle['correo'] ?? $detalle['correo_institucional']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control" value="<?= $detalle['telefono']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña(Opcional)</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control"
                                    value="<?= htmlspecialchars($datos_adicionales['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                <button type="button" class="toggle-password-btn" id="togglePassword">
                                    <span id="iconoPassword">👁️</span>
                                </button>
                            </div>
                        </div>
                        <button type="submit" name="editar_usuario" class="btn btn-primary w-100">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    <div class="text-center mt-4">
        <a href="gestionar_usuarios.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
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
