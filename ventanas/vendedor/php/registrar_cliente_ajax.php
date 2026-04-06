<?php
session_start();
require '../../login/php/conexion.php';

// Le indicamos al navegador que es una respuesta JSON
header('Content-Type: application/json');

// ====================================================================
// SEGURIDAD: Bloquear peticiones externas o sin sesión
// ====================================================================
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // La limpieza básica combinada con sentencias preparadas
    $cedula = trim($data['cedula']);
    $nombre = trim($data['nombre']);
    $apellido = trim($data['apellido']);
    $email = trim($data['email']);
    $tlf = trim($data['tlf']);
    $dir = trim($data['dir']);

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
        echo json_encode(['success' => false, 'error' => 'Error al registrar en BD']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos vacíos']);
}
$conn->close();
?>