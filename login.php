<?php
include 'conexion.php'; // Incluye el archivo de conexión

session_start();

// Verificar si ya hay una sesión activa
if (isset($_SESSION['id_usuario'])) {
    if ($_SESSION['rol'] == 'Administrador') {
        header('Location: modules/admin_dashboard.php');
    } elseif ($_SESSION['rol'] == 'Encargado') {
        header('Location: modules/encargado_dashboard.php');
    } elseif ($_SESSION['rol'] == 'Estudiante') {
        header('Location: modules/estudiante_dashboard.php');
    }
    exit;
}

$error = ''; // Inicializamos la variable de error
$mensaje = ''; // Inicializamos la variable para mensajes informativos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo']); // Capturar correo
    $password = $_POST['password'];

    // Verificar si el usuario existe
    $query = "SELECT id_usuario, password, rol FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña según el rol
        if ($user['rol'] == 'Administrador') {
            // Si es administrador, no usar password_verify, comparar directamente
            if ($password === $user['password']) {
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigir al dashboard del administrador
                header("Location: modules/admin_dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            // Para los demás usuarios, usar password_verify
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigir según el rol
                if ($user['rol'] == 'Encargado') {
                    header("Location: modules/encargado_dashboard.php");
                } elseif ($user['rol'] == 'Estudiante') {
                    header("Location: modules/estudiante_dashboard.php");
                }
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        }
    } else {
        $error = "No se encontró una cuenta con ese correo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>
<header>
    <img src="img/logo_univalle.jpg" alt="Logo Universidad del Valle">
    <h1>Universidad del Valle</h1>
</header>

<main>
    <div class="login-container d-flex align-items-center justify-content-center vh-100">
        <div class="login-card p-4 shadow rounded">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-info text-center">
                    <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" id="correo" name="correo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <button type="button" class="toggle-password-btn" id="togglePassword">
                            <span id="iconoPassword">👁️</span>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
            <?php if (!empty($error)): ?>
                <p class="text-danger mt-3 text-center"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>

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

