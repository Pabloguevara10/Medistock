<?php
session_start();
// OJO: Ajusta esta ruta
require '../../login/php/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = intval($_POST['id']);
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $laboratorio = trim($_POST['laboratorio']);
    $categoria = $_POST['categoria'];
    $presentacion = trim($_POST['presentacion']);
    $precio_compra = floatval($_POST['precio_compra']);
    $precio_venta = floatval($_POST['precio_venta']);
    $stock = intval($_POST['stock']);
    $fecha_llegada = $_POST['fecha_llegada'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Determinar nuevo estado
    $estado = 'Óptimo';
    $hoy = date("Y-m-d");
    if ($fecha_vencimiento < $hoy) {
        $estado = 'Vencido';
    } elseif ($stock <= 10) {
        $estado = 'Crítico';
    }

    $sql = "UPDATE productos SET 
            codigo = ?, nombre = ?, categoria = ?, presentacion = ?, 
            stock = ?, precio_compra = ?, precio_venta = ?, laboratorio = ?, 
            fecha_llegada = ?, fecha_vencimiento = ?, estado = ? 
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);
    // Agregamos una 'i' extra al final para el ID
    $stmt->bind_param("ssssiddssssi", $codigo, $nombre, $categoria, $presentacion, $stock, $precio_compra, $precio_venta, $laboratorio, $fecha_llegada, $fecha_vencimiento, $estado, $id);

    if ($stmt->execute()) {
        header("Location: ../inventario.php?status=updated");
        exit();
    } else {
        header("Location: ../inventario.php?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../inventario.php");
}
?> 