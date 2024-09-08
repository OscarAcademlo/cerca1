<?php
session_start(); // Iniciar sesión
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php'); // Redirigir si no está autenticado
    exit;
}


include 'db.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];
    $rutaImagen = 'img/' . basename($imagen); // Asegura un nombre de archivo seguro

    // Verificar que el directorio 'img' exista
    if (!is_dir('img')) {
        mkdir('img', 0775, true); // Crear el directorio si no existe con permisos de escritura
    }

    // Mostrar el error de carga de archivos si hay alguno
    if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        echo "Error en la carga de archivos: " . $_FILES['imagen']['error'];
        exit;
    }

    // Intentar mover la imagen al servidor
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
        $sql = "INSERT INTO articulos (nombre, imagen, precio) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssd', $nombre, $imagen, $precio); // 'ssd' significa string, string, double
        if ($stmt->execute()) {
            header("Location: admin.php");
            exit; // Detiene la ejecución después de la redirección
        } else {
            echo "Error en la base de datos: " . $stmt->error;
        }
    } else {
        echo "Error al subir la imagen. Verifica los permisos del directorio 'img/'.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Agregar Nuevo Artículo</h1>
    <form action="create.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Artículo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Artículo</label>
            <input type="file" class="form-control" id="imagen" name="imagen" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio del Artículo</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
        <!-- Botones de Crear y Volver a Home -->
        <button type="submit" class="btn btn-success">Crear Artículo</button>
        <a href="index.php" class="btn btn-secondary">Volver a Home</a>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
