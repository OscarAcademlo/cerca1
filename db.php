<?php
$servername = "localhost"; // O la dirección de tu servidor
$username = "root"; // Cambia a tu usuario de base de datos
$password = ""; // Cambia a tu contraseña de base de datos
$dbname = "tienda"; // El nombre de tu base de datos

// Crear la conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
