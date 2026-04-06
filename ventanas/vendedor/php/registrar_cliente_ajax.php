<?php
session_start();
require '../../login/php/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $cedula = $data['cedula'];
    $nombre = $data['nombre'];
    $apellido = $data['apellido'];
    $email = $data['email'];
    $tlf = $data['tlf'];
    $dir = $data['dir'];

    $sql = "INSERT INTO clientes (cedula, nombre, apellido, email, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $cedula, $nombre, $apellido, $email, $tlf, $dir);

    if ($stmt->execute()) {
        // Una vez registrado, lo seleccionamos automáticamente para la sesión
        $_SESSION['cliente_actual'] = [
            'id' => $conn->insert_id,
            'cedula' => $cedula,
            'nombre' => $nombre,
            'apellido' => $apellido
        ];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}