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

    // Validamos que no haya campos vacíos sin mostrar rutas
    if (empty($nombre) || empty($apellido) || empty($cedula) || empty($email) || empty($password) || empty($rol)) {
        header("Location: ../registro.php?status=error_empty");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: ../registro.php?status=error_password_match");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Intentamos la inserción directamente. Los campos UNIQUE en SQL protegerán la integridad.
    $stmt = $conn->prepare("INSERT INTO usuarios (tipo_doc, cedula, nombre, apellido, email, telefono, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $tipo_doc, $cedula, $nombre, $apellido, $email, $telefono, $hashed_password, $rol);

    try {
        if ($stmt->execute()) {
            header("Location: ../login.php?status=success_registro");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // Error 1062 es el código de MySQL para "Entrada duplicada"
        if ($e->getCode() === 1062) {
            // Verificamos qué campo falló para ser específicos sin exponer la estructura
            if (str_contains($e->getMessage(), 'email')) {
                header("Location: ../registro.php?status=error_email_exists");
            } elseif (str_contains($e->getMessage(), 'cedula')) {
                header("Location: ../registro.php?status=error_cedula_exists");
            } else {
                header("Location: ../registro.php?status=error_exists");
            }
        } else {
            // Error genérico de base de datos para no dar pistas a un atacante
            header("Location: ../registro.php?status=error_db");
        }
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../registro.php");
    exit();
}
?>