<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php'); // Redirigir si no está autenticado
    exit;
}

// Incluir el archivo de conexión a la base de datos
include 'db.php';

// Verificar que el usuario exista en la base de datos y esté autenticado
$stmt = $conexion->prepare('SELECT * FROM usuarios WHERE nombre = ?');
$stmt->bind_param('s', $_SESSION['nombre']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar si el usuario es válido
    if (!isset($user['id'])) {
        session_destroy(); // Destruir la sesión si el usuario no es válido
        header('Location: login.php'); // Redirigir al formulario de inicio de sesión
        exit;
    }
} else {
    header('Location: login.php'); // Redirigir si el usuario no se encuentra
    exit;
}

// Obtener los artículos de la base de datos
$sql = "SELECT * FROM articulos";
$result = $conexion->query($sql);

// Cerrar la declaración preparada
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Artículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Listado de Artículos</h1>
    <div class="mb-3">
        <a href="create.php" class="btn btn-primary">Agregar Nuevo Artículo</a>
        <a href="index.php" class="btn btn-secondary ms-2">Volver al Home</a>
        <a href="logout.php" class="btn btn-danger ms-2">Cerrar Sesión</a> <!-- Botón para cerrar sesión -->
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><img src="img/<?php echo $row['imagen']; ?>" width="50" height="50"></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No hay artículos disponibles</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
