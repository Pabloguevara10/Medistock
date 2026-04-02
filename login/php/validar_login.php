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

            // Redirección dependiendo del rol (Opcional, o todos a menu.html)
            header("Location: menu.html"); 
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No se encontró ninguna cuenta con ese correo.'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.html");
    exit();
}
?>