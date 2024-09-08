<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM articulos WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        header("Location: admin.php");
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
}
?>
