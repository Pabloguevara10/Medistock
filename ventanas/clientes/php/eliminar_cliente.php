<?php
session_start();
require '../../login/php/conexion.php';

// ====================================================================
// SEGURIDAD: Bloqueo de acceso directo a eliminación
// ====================================================================
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Protección vital para IDs enviados por URL

    try {
        $sql = "DELETE FROM clientes WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        header("Location: ../clientes.php?status=deleted");
    } catch (Exception $e) {
        // Si el cliente tiene ventas asociadas, MySQL lanzará una excepción por la llave foránea
        header("Location: ../clientes.php?status=error_delete");
    }
    $stmt->close();
} else {
    header("Location: ../clientes.php");
}
$conn->close();
exit();
?>