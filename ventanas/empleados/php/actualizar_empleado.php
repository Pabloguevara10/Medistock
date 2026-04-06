<?php
session_start(); 
require '../../login/php/conexion.php';

// SEGURIDAD: Expulsar si no es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiamos los datos
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $rol = $_POST['rol'];
    $id = intval($_POST['id']); // Forzamos a entero

    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, rol=? WHERE id=?");
    $stmt->bind_param("ssssi", $nombre, $apellido, $telefono, $rol, $id);
    
    if ($stmt->execute()) {
        header("Location: ../empleados.php?status=updated");
    } else {
        header("Location: ../empleados.php?status=error");
    }
    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../empleados.php");
    exit();
}
?>