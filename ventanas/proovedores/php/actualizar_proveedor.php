<?php
session_start();
require '../../login/php/conexion.php'; 

// SEGURIDAD: Restringir acceso
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    
    // Protección: Convertimos a entero obligatoriamente
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $rif = trim($_POST['rif']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $categorias = trim($_POST['categorias']);
    $direccion = trim($_POST['direccion']);

    $stmt = $conn->prepare("UPDATE proveedores SET nombre=?, rif=?, categorias=?, telefono=?, correo=?, direccion=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nombre, $rif, $categorias, $telefono, $correo, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=updated");
    } else {
        header("Location: ../proovedores.php?status=error_update");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>