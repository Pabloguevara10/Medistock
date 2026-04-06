<?php
session_start(); require '../../login/php/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'Administrador') {
    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, rol=? WHERE id=?");
    $stmt->bind_param("ssssi", $_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['rol'], $_POST['id']);
    $stmt->execute() ? header("Location: ../empleados.php?status=updated") : header("Location: ../empleados.php?status=error");
}