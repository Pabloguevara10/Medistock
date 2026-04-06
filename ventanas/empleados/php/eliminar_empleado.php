<?php
session_start(); 
require '../../login/php/conexion.php';

// SEGURIDAD: Expulsar si no es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id'])) {
    $id_a_eliminar = intval($_GET['id']);

    // SEGURIDAD: Evitar que el administrador se elimine a sí mismo
    if ($id_a_eliminar !== intval($_SESSION['user_id'])) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $id_a_eliminar);
        
        if ($stmt->execute()) {
            header("Location: ../empleados.php?status=deleted");
        } else {
            header("Location: ../empleados.php?status=error");
        }
        $stmt->close();
    } else {
        // Redirigir con un error específico si intenta borrarse a sí mismo
        header("Location: ../empleados.php?status=error_self_delete");
    }
} else {
    header("Location: ../empleados.php");
}
$conn->close();
exit();
?>