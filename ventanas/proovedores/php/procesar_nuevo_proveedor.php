<?php
session_start();
// Ajustamos la ruta para salir de /php/ y /proovedores/ y entrar a /login/php/
require '../../login/php/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Limpiamos los datos recibidos
    $nombre = trim($_POST['nombre']);
    $rif = trim($_POST['rif']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $categorias = trim($_POST['categorias']);
    $direccion = trim($_POST['direccion']);

    // Preparamos la inserción para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, rif, categorias, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?, ?)");
    
    // "ssssss" significa que mandaremos 6 strings (cadenas de texto)
    $stmt->bind_param("ssssss", $nombre, $rif, $categorias, $telefono, $correo, $direccion);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=added");
        exit();
    } else {
        header("Location: ../proovedores.php?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>