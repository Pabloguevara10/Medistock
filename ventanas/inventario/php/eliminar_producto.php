<?php
session_start();
require '../../login/php/conexion.php'; 

// ====================================================================
// SEGURIDAD: Proteger la ruta de eliminación directa
// ====================================================================
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // intval asegura que solo pasen números, bloqueando inyecciones

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../inventario.php?status=deleted");
    } else {
        header("Location: ../inventario.php?status=error");
    }
    $stmt->close();
} else {
    header("Location: ../inventario.php");
}
$conn->close();
exit();
?>