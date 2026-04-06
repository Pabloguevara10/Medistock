<?php
session_start();
require '../../login/php/conexion.php'; 

// SEGURIDAD: Evitar que cualquiera pueda inyectar stock fantasma
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../../login/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cantidades'])) {
    
    $cantidades = $_POST['cantidades']; 
    $exito = true;

    $sql = "UPDATE productos SET 
                stock = stock + ?, 
                estado = CASE 
                            WHEN (stock + ?) > 10 AND estado = 'Crítico' THEN 'Óptimo' 
                            ELSE estado 
                         END 
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);

    foreach ($cantidades as $id_producto => $cantidad_pedida) {
        
        if (!empty($cantidad_pedida) && is_numeric($cantidad_pedida) && $cantidad_pedida > 0) {
            
            // Forzamos tipos de datos puros antes de operar
            $id = intval($id_producto);
            $cant = intval($cantidad_pedida);
            
            $stmt->bind_param("iii", $cant, $cant, $id);
            
            if (!$stmt->execute()) {
                $exito = false;
            }
        }
    }

    $stmt->close();
    $conn->close();

    if ($exito) {
        header("Location: ../proovedores.php?status=ordered");
    } else {
        header("Location: ../proovedores.php?status=error_order");
    }
    exit();
} else {
    header("Location: ../proovedores.php");
    exit();
}
?>