<?php
session_start();
// Ruta para conexión desde /proovedores/php/
require '../../login/php/conexion.php'; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Preparamos la eliminación por seguridad
    $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=deleted");
        exit();
    } else {
        header("Location: ../proovedores.php?status=error_delete");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>