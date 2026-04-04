<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "MediStock"; // Cambiado a tu nueva base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
// Establecer el charset para evitar problemas con caracteres especiales
$conn->set_charset("utf8mb4");
?>