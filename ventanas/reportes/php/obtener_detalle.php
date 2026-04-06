<?php
session_start();
require '../../login/php/conexion.php';

// ====================================================================
// SEGURIDAD: Bloquear acceso si no hay sesión iniciada
// ====================================================================
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'mensaje' => 'Acceso no autorizado']);
    exit();
}

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_venta = intval($_GET['id']);

    // 1. Buscamos los items de la venta
    $sql_detalles = "SELECT nombre_producto, cantidad, precio_unitario, subtotal FROM ventas_detalle WHERE id_venta = ?";
    $stmt = $conn->prepare($sql_detalles);
    $stmt->bind_param("i", $id_venta);
    $stmt->execute();
    $res_detalles = $stmt->get_result();

    $detalles = [];
    while($row = $res_detalles->fetch_assoc()) {
        $detalles[] = $row;
    }

    // 2. Buscamos el total de esa venta
    $sql_total = "SELECT total_venta FROM ventas WHERE id = ?";
    $stmt_tot = $conn->prepare($sql_total);
    $stmt_tot->bind_param("i", $id_venta);
    $stmt_tot->execute();
    $total = $stmt_tot->get_result()->fetch_assoc()['total_venta'];

    echo json_encode([
        'detalles' => $detalles,
        'total' => $total
    ]);
} else {
    echo json_encode(['error' => true]);
}
?>