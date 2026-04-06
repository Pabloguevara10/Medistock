<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../login.php?status=error_empty");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, nombre, apellido, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_completo'] = $user['nombre'] . " " . $user['apellido'];
            $_SESSION['rol'] = $user['rol'];

            header("Location: ../../menuprincipal/menu.php"); 
            exit();
        } else {
            header("Location: ../login.php?status=error_password");
            exit();
        }
    } else {
        header("Location: ../login.php?status=error_notfound");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../login.php");
    exit();
}
?>