<?php


session_start(); // Iniciar sesión
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php'); // Redirigir si no está autenticado
    exit;
}



include 'db.php';

// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM articulos WHERE id = $id";
    $result = $conexion->query($sql);
    $articulo = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];

    if ($imagen) {
        $rutaImagen = 'img/' . $imagen;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            $sql = "UPDATE articulos SET nombre='$nombre', imagen='$imagen', precio='$precio' WHERE id=$id";
        } else {
            echo "Error al subir la imagen. Verifica los permisos del directorio.";
            exit;
        }
    } else {
        $sql = "UPDATE articulos SET nombre='$nombre', precio='$precio' WHERE id=$id";
    }

    if ($conexion->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Editar Artículo</h1>
    <form action="update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $articulo['id']; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Artículo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $articulo['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Artículo (dejar en blanco para mantener la imagen actual)</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio del Artículo</label>
            <input type="text" class="form-control" id="precio" name="precio" value="<?php echo $articulo['precio']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
