<?php
session_start();
require '../../login/php/conexion.php'; 

// SEGURIDAD: Restringir acceso
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = trim($_POST['nombre']);
    $rif = trim($_POST['rif']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $categorias = trim($_POST['categorias']);
    $direccion = trim($_POST['direccion']);

    $stmt = $conn->prepare("INSERT INTO proveedores (nombre, rif, categorias, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $rif, $categorias, $telefono, $correo, $direccion);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=added");
    } else {
        header("Location: ../proovedores.php?status=error");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>