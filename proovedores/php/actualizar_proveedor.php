<?php
session_start();
// Ruta para conexión desde /proovedores/php/
require '../../login/php/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    
    // Limpiamos y recibimos datos
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $rif = trim($_POST['rif']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $categorias = trim($_POST['categorias']);
    $direccion = trim($_POST['direccion']);

    // Preparamos la actualización (UPDATE)
    $stmt = $conn->prepare("UPDATE proveedores SET nombre=?, rif=?, categorias=?, telefono=?, correo=?, direccion=? WHERE id=?");
    
    // "ssssssi" -> 6 strings y 1 entero (el id)
    $stmt->bind_param("ssssssi", $nombre, $rif, $categorias, $telefono, $correo, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=updated");
        exit();
    } else {
        header("Location: ../proovedores.php?status=error_update");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>