<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        die("<script>alert('Por favor, ingresa tu correo y contraseña.'); window.history.back();</script>");
    }

    // Traemos también el nombre y apellido para mostrar un saludo en el menú
    $stmt = $conn->prepare("SELECT id, nombre, apellido, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Guardamos todo en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_completo'] = $user['nombre'] . " " . $user['apellido'];
            $_SESSION['rol'] = $user['rol'];

            // REDIRECCIÓN CORREGIDA: Salimos de php/, salimos de login/ y entramos a menu.php en la raíz
            header("Location: ../../menuprincipal/menu.php"); 
            exit();
        } else {
            // REDIRECCIÓN CORREGIDA: Salimos de php/ y entramos a login.php
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='../login.php';</script>";
        }
    } else {
        // REDIRECCIÓN CORREGIDA
        echo "<script>alert('No se encontró ninguna cuenta con ese correo.'); window.location.href='../login.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // REDIRECCIÓN CORREGIDA
    header("Location: ../login.php");
    exit();
}
?>