<?php
session_start();
require '../../login/php/conexion.php';

// ====================================================================
// SEGURIDAD: Verificar que el usuario esté autenticado y sea Administrador
// ====================================================================
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']); // Protección: Forzamos a que sea un número entero
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);

    try {
        $sql = "UPDATE clientes SET cedula=?, nombre=?, apellido=?, telefono=?, email=?, direccion=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        
        // La "s" es para string, la "i" para integer
        $stmt->bind_param("ssssssi", $cedula, $nombre, $apellido, $telefono, $email, $direccion, $id);
        
        if ($stmt->execute()) {
            header("Location: ../clientes.php?status=updated");
        } else {
            header("Location: ../clientes.php?status=error");
        }
        $stmt->close();
    } catch (Exception $e) {
        header("Location: ../clientes.php?status=error");
    }
    $conn->close();
    exit();
} else {
    header("Location: ../clientes.php");
    exit();
}
?>