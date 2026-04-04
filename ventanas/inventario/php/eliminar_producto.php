<?php
session_start();
// OJO: Ajusta esta ruta igual que en procesar_nuevo_producto.php
require '../../login/php/conexion.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../inventario.php?status=deleted");
        exit();
    } else {
        header("Location: ../inventario.php?status=error");
        exit();
    }
    $stmt->close();
} else {
    header("Location: ../inventario.php");
}
$conn->close();
?>