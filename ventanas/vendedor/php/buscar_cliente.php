<?php
session_start();
require '../../login/php/conexion.php';

// Le decimos al navegador que siempre devolveremos un JSON (evita que se impriman errores crudos en pantalla)
header('Content-Type: application/json');

$cedula = $_GET['cedula'] ?? '';

// Si la cédula está vacía, cortamos el proceso inmediatamente
if (empty(trim($cedula))) {
    echo json_encode(['encontrado' => false]);
    exit();
}

try {
    // ====================================================================
    // CONSULTA PREPARADA: PROTECCIÓN TOTAL CONTRA INYECCIÓN SQL
    // ====================================================================
    $sql = "SELECT * FROM clientes WHERE cedula = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // La "s" indica que el parámetro es un String (texto seguro)
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $cliente = $res->fetch_assoc();
            $_SESSION['cliente_actual'] = $cliente; // Guardamos en sesión
            echo json_encode(['encontrado' => true]);
        } else {
            echo json_encode(['encontrado' => false]);
        }
        
        $stmt->close();
    } else {
        // Si hay un error interno de SQL, devolvemos un JSON limpio, no un error de pantalla
        echo json_encode(['encontrado' => false, 'error' => 'Error de base de datos']);
    }

} catch (Exception $e) {
    // Atrapamos cualquier otro error crítico y lo silenciamos
    echo json_encode(['encontrado' => false, 'error' => 'Error del servidor']);
}

$conn->close();
?>