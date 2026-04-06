<?php
session_start();
require '../../login/php/conexion.php';

if (!isset($_SESSION['cliente_actual']) || empty($_SESSION['carrito'])) {
    header("Location: ../vendedor.php"); exit();
}

$cliente = $_SESSION['cliente_actual'];
$total = 0;
foreach ($_SESSION['carrito'] as $i) { $total += $i['precio'] * $i['cantidad']; }

$conn->begin_transaction();
try {
    // Registrar Venta vinculada al cliente
    $sql = "INSERT INTO ventas (id_cliente, cedula_cliente, nombre_cliente, telefono_cliente, total_venta, id_vendedor) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $nombre_completo = $cliente['nombre'] . " " . $cliente['apellido'];
    
    // Asumimos que clientes ya tiene 'telefono' guardado en sesión si lo necesitas o lo buscas de nuevo
    $tlf = $cliente['telefono'] ?? '0000'; 
    $stmt->bind_param("isssdi", $cliente['id'], $cliente['cedula'], $nombre_completo, $tlf, $total, $_SESSION['user_id']);
    $stmt->execute();
    $factura = $conn->insert_id;

    // Guardar detalles de la factura
    $sql_detalle = "INSERT INTO ventas_detalle (id_venta, id_producto, nombre_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_det = $conn->prepare($sql_detalle);

    // ====================================================================
    // CORRECCIÓN DE SEGURIDAD: Preparamos el UPDATE del stock
    // ====================================================================
    $sql_update_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
    $stmt_stock = $conn->prepare($sql_update_stock);

    foreach ($_SESSION['carrito'] as $i) {
        $subtotal_item = $i['precio'] * $i['cantidad'];
        
        // 1. Guardar el ítem en la factura
        $stmt_det->bind_param("iisidd", $factura, $i['id'], $i['nombre'], $i['cantidad'], $i['precio'], $subtotal_item);
        $stmt_det->execute();

        // 2. Descontar del inventario de forma 100% segura
        $stmt_stock->bind_param("ii", $i['cantidad'], $i['id']);
        $stmt_stock->execute();
    }
    
    // Cerramos los statements por buenas prácticas
    $stmt_stock->close();
    $stmt_det->close();
    $stmt->close();

    $conn->commit();
    $_SESSION['carrito'] = [];
    unset($_SESSION['cliente_actual']); // Limpiar cliente para la siguiente venta
    header("Location: ../vendedor.php?status=venta_exitosa&factura=" . $factura);
} catch (Exception $e) {
    $conn->rollback();
    header("Location: ../vendedor.php?status=error_db");
}
?>