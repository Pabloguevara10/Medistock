<?php
// Iniciar sesión y requerir conexión
session_start();
require '../../login/php/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recibir y limpiar los datos del formulario
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

    // 2. Validaciones básicas
    if (empty($codigo) || empty($nombre) || empty($categoria) || empty($precio_compra) || empty($precio_venta)) {
        die("<script>alert('Faltan campos obligatorios.'); window.history.back();</script>");
    }

    // 3. Determinar el "Estado" automáticamente
    $estado = 'Óptimo';
    $hoy = date("Y-m-d");

    if ($fecha_vencimiento < $hoy) {
        $estado = 'Vencido';
    } elseif ($stock <= 10) { // Consideramos crítico si hay 10 o menos unidades
        $estado = 'Crítico';
    }

    // 4. Verificar si el código de barras ya existe
    $stmt_check = $conn->prepare("SELECT id FROM productos WHERE codigo = ?");
    $stmt_check->bind_param("s", $codigo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('Error: Ya existe un producto con este código de barras.'); window.history.back();</script>";
        $stmt_check->close();
        exit();
    }
    $stmt_check->close();

    // 5. Insertar el producto en la base de datos
    $sql = "INSERT INTO productos (codigo, nombre, categoria, presentacion, stock, precio_compra, precio_venta, laboratorio, fecha_llegada, fecha_vencimiento, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    
    // "ssssiddssss" significa: string, string, string, string, int, double, double, string, string, string, string
    $stmt->bind_param("ssssiddssss", $codigo, $nombre, $categoria, $presentacion, $stock, $precio_compra, $precio_venta, $laboratorio, $fecha_llegada, $fecha_vencimiento, $estado);

    if ($stmt->execute()) {
        header("Location: ../inventario.php?status=added");
        exit();
    } else {
        header("Location: ../inventario.php?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Si acceden directamente al archivo sin enviar el formulario
    header("Location: inventario.php");
    exit();
}
?>