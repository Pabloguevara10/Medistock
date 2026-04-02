<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_doc = $_POST['tipo_doc'];
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($cedula) || empty($email) || empty($password) || empty($rol)) {
        die("<script>alert('Por favor, completa todos los campos obligatorios.'); window.history.back();</script>");
    }

    if ($password !== $confirm_password) {
        die("<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>");
    }

    // Verificar si la cédula o el email ya existen
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR cedula = ?");
    $stmt_check->bind_param("ss", $email, $cedula);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('Error: El correo electrónico o la cédula ya están registrados.'); window.history.back();</script>";
        $stmt_check->close();
        exit();
    }
    $stmt_check->close();

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en MediStock
    $stmt = $conn->prepare("INSERT INTO usuarios (tipo_doc, cedula, nombre, apellido, email, telefono, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $tipo_doc, $cedula, $nombre, $apellido, $email, $telefono, $hashed_password, $rol);

    if ($stmt->execute()) {
        echo "<script>alert('¡Personal registrado exitosamente!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error al registrar: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: registro.html");
    exit();
}
?>