<?php
session_start();

// Verificar si el usuario está autenticado y es Encargado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 'Encargado') {
    header("Location: ../login.php");
    exit;
}

include '../conexion.php'; // Conexión a la base de datos

$mensaje = "";  // Variable para mostrar mensajes de error o éxito
$estudiante = null;  // Inicializa la variable estudiante

// Verificar si se ha enviado un ID para editar
if (isset($_POST['id_estudiante'])) {
    $id_estudiante = intval($_POST['id_estudiante']);

    // Consultar los datos del estudiante desde la tabla 'estudiante' y 'usuario'
    $query = "
        SELECT e.id_estudiante, e.nombre, e.correo_institucional, e.telefono, e.id_curso, u.correo, u.password
        FROM estudiante e
        JOIN usuario u ON e.id_usuario = u.id_usuario
        WHERE e.id_estudiante = $id_estudiante
    ";
    $result = mysqli_query($conn, $query);
    $estudiante = mysqli_fetch_assoc($result);

    if (!$estudiante) {
        $mensaje = "Estudiante no encontrado.";
    }
}

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre']) && isset($_POST['correo_institucional']) && isset($_POST['correo'])) {
    $id_estudiante = intval($_POST['id_estudiante']);
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo_institucional = mysqli_real_escape_string($conn, $_POST['correo_institucional']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $id_curso = intval($_POST['id_curso']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Si la contraseña fue modificada, cifrarla
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);  // Cifrar la contraseña
    } else {
        // Si no se ingresó una nueva contraseña, dejar la contraseña actual intacta
        $password = $estudiante['password']; 
    }

    // Actualizar los datos del estudiante en la tabla 'estudiante'
    $sql_estudiante = "UPDATE estudiante SET nombre = '$nombre', correo_institucional = '$correo_institucional', telefono = '$telefono', id_curso = $id_curso WHERE id_estudiante = $id_estudiante";
    
    // Actualizar los datos del usuario (incluyendo la contraseña cifrada)
    $sql_usuario = "UPDATE usuario SET correo = '$correo', password = '$password' WHERE id_usuario = (SELECT id_usuario FROM estudiante WHERE id_estudiante = $id_estudiante)";

    if (mysqli_query($conn, $sql_estudiante) && mysqli_query($conn, $sql_usuario)) {
        $mensaje = "Estudiante y usuario actualizados con éxito.";
    } else {
        $mensaje = "Error al actualizar datos: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Editar Estudiante</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de edición -->
        <?php if ($estudiante): ?>
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id_estudiante" value="<?= htmlspecialchars($estudiante['id_estudiante'], ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($estudiante['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo_institucional" class="form-label">Correo Institucional</label>
                        <input type="email" id="correo_institucional" name="correo_institucional" class="form-control" value="<?= htmlspecialchars($estudiante['correo_institucional'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="<?= htmlspecialchars($estudiante['telefono'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_curso" class="form-label">Curso</label>
                        <input type="text" id="id_curso" name="id_curso" class="form-control" value="<?= htmlspecialchars($estudiante['id_curso'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo (Usuario)</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($estudiante['correo'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña(Opcional)</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" value="" placeholder="Deja vacío si no deseas cambiarla">
                            <button type="button" class="toggle-password-btn" id="togglePassword">
                                <span id="iconoPassword">👁️</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Actualizar Estudiante</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista de Estudiantes -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Lista de Estudiantes</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Estudiante</th>
                            <th>Correo Institucional</th>
                            <th>Curso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar solo estudiantes
                        $query = "SELECT e.id_estudiante, e.correo_institucional, e.id_curso FROM estudiante e";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_estudiante'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($row['correo_institucional'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($row['id_curso'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="id_estudiante" value="<?= htmlspecialchars($row['id_estudiante'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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



