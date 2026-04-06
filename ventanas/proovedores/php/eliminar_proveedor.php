<?php
session_start();
require '../../login/php/conexion.php'; 

// SEGURIDAD: Restringir acceso
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Filtrado seguro del ID

    $stmt = $conn->prepare("DELETE FROM proveedores WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../proovedores.php?status=deleted");
    } else {
        header("Location: ../proovedores.php?status=error_delete");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>