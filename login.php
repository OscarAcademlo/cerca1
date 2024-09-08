<?php
session_start(); // Iniciar sesión

include 'db.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    // Verificar el usuario en la base de datos
    $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE nombre = ?');
    $stmt->bind_param('s', $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar la contraseña usando bcrypt
        if (password_verify($password, $user['password'])) {
            $_SESSION['nombre'] = $nombre; // Guardar el nombre de usuario en la sesión
            header('Location: admin.php'); // Redirigir a la página de administración
            exit;
        } else {
            echo "<script>alert('Nombre de usuario o contraseña incorrectos');</script>";
        }
    } else {
        echo "<script>alert('Nombre de usuario o contraseña incorrectos');</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Iniciar Sesión</h1>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        <a href="index.php" class="btn btn-secondary ms-2">Volver al Home</a> <!-- Botón para volver al Home -->
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
