<?php
session_start();
require '../../login/php/conexion.php'; 

// Verificamos que se haya enviado el formulario con cantidades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cantidades'])) {
    
    $cantidades = $_POST['cantidades']; // Es un arreglo [ id_producto => cantidad_a_sumar ]
    $exito = true;

    // Preparamos la consulta. Sumamos el stock nuevo al actual.
    // Además, usamos CASE para que si el stock pasa de 10 y estaba "Crítico", cambie a "Óptimo".
    $sql = "UPDATE productos SET 
                stock = stock + ?, 
                estado = CASE 
                            WHEN (stock + ?) > 10 AND estado = 'Crítico' THEN 'Óptimo' 
                            ELSE estado 
                         END 
            WHERE id = ?";
            
    $stmt = $conn->prepare($sql);

    // Recorremos todos los productos que llegaron en el formulario
    foreach ($cantidades as $id_producto => $cantidad_pedida) {
        
        // Solo actualizamos si el usuario escribió un número mayor a 0
        if (!empty($cantidad_pedida) && is_numeric($cantidad_pedida) && $cantidad_pedida > 0) {
            
            $id = intval($id_producto);
            $cant = intval($cantidad_pedida);
            
            // "iii" -> 3 enteros (cantidad, cantidad, id)
            $stmt->bind_param("iii", $cant, $cant, $id);
            
            if (!$stmt->execute()) {
                $exito = false; // Si falla uno, marcamos error
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