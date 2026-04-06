<?php
session_start(); require '../../login/php/conexion.php';
if (isset($_GET['id']) && $_SESSION['rol'] === 'Administrador' && $_GET['id'] != $_SESSION['user_id']) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute() ? header("Location: ../empleados.php?status=deleted") : header("Location: ../empleados.php?status=error");
}