<?php
session_start();
require '../../login/php/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

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
} else {
    header("Location: ../clientes.php");
}
$conn->close();
?>