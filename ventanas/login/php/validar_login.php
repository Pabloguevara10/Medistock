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
            // Guardamos los datos en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_completo'] = $user['nombre'] . " " . $user['apellido'];
            $_SESSION['rol'] = $user['rol'];

            // ==========================================
            // MAGIA: REDIRECCIÓN DEPENDIENDO DEL ROL
            // ==========================================
            if ($user['rol'] === 'Administrador') {
                // El Admin va a su panel de control principal
                header("Location: ../../menuprincipal/menu.php"); 
            } else if ($user['rol'] === 'Vendedor') {
                // El Vendedor va directo a su punto de venta
                header("Location: ../../vendedor/vendedor.php"); 
            } else {
                // Por si acaso hay un rol no definido
                header("Location: ../login.php?status=error_rol");
            }
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