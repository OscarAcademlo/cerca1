<?php
session_start(); // Iniciar sesión
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php'); // Redirigir si no está autenticado
    exit;
}


include 'db.php'; // Incluir el archivo de conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conexion->prepare("DELETE FROM articulos WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' indica que se espera un parámetro de tipo entero

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit; // Detener la ejecución después de la redirección
    } else {
        echo "Error al eliminar: " . $stmt->error;
    }

    $stmt->close(); // Cerrar la declaración preparada
} else {
    echo "ID no especificado.";
}

$conexion->close(); // Cerrar la conexión a la base de datos
?>
