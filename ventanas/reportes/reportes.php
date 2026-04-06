<?php
session_start();
require '../login/php/conexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') { 
    header("Location: ../login/login.php"); exit(); 
}

// Consultar todas las ventas cruzadas con el vendedor
$sql_ventas = "SELECT v.id as recibo, v.fecha_venta, v.cedula_cliente, v.nombre_cliente, v.total_venta, u.nombre as vendedor_nombre, u.apellido as vendedor_apellido 
               FROM ventas v 
               LEFT JOIN usuarios u ON v.id_vendedor = u.id 
               ORDER BY v.fecha_venta DESC";
$resultado = $conn->query($sql_ventas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MediStock - Reportes</title>
    <link rel="stylesheet" href="../../css/globals.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="app-container">
        <header class="header">
          <div class="header-left">
            <img class="logo" src="../../img/logo.png" />
            <img class="texto" src="../../img/tipografia.png" />
          </div>
          <div class="header-user">
            <div class="user-avatar">A</div>
            <span><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></span>
          </div>
        </header>

        <aside class="sidebar">
          <div class="sidebar-title">MENÚ PRINCIPAL</div>
          <a href="../menuprincipal/menu.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/home.png"> <span>Inicio</span></a>
          <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/inventory.png"> <span>Inventario</span></a>
          <div class="sidebar-item active"><img src="../../img/report.png"> <span>Reportes</span></div>
          <a href="../proovedores/proovedores.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/cargamento.png"> <span>Proveedores</span></a>
          <a href="../empleados/empleados.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/empleados.png"> <span>Empleados</span></a>
          <a href="../login/login.php" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;"><img src="../../img/salir.png"> <span>Salir</span></a>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Historial de Ventas</h1>
            
            <div class="table-section">
                <div class="table-responsive">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Recibo N°</th>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Atendido Por</th>
                                <th>Total Facturado</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($v = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo str_pad($v['recibo'], 5, "0", STR_PAD_LEFT); ?></strong></td>
                                    <td><?php echo date("d/m/Y - h:i A", strtotime($v['fecha_venta'])); ?></td>
                                    <td><?php echo htmlspecialchars($v['nombre_cliente']); ?><br><small>V-<?php echo htmlspecialchars($v['cedula_cliente']); ?></small></td>
                                    <td><?php echo htmlspecialchars($v['vendedor_nombre'] . ' ' . $v['vendedor_apellido']); ?></td>
                                    <td style="color:#3b9b4a; font-weight:bold;">$<?php echo number_format($v['total_venta'], 2); ?></td>
                                    <td>
                                        <button onclick="verFactura(<?php echo $v['recibo']; ?>)" class="btn-submit-pill" style="font-size:12px; padding: 5px 15px; width:auto;">👁️ Ver Recibo</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../../fonds/sweetalert.cjs"></script>
    <script>
        // Función de seguridad para purificar texto y evitar DOM XSS
        function escapeHTML(str) {
            if (!str) return '';
            return str.toString().replace(/[&<>'"]/g, function(tag) {
                const charsToReplace = { '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' };
                return charsToReplace[tag] || tag;
            });
        }

        function verFactura(id_venta) {
            fetch('php/obtener_detalle.php?id=' + id_venta)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    Swal.fire('Error', 'No se encontraron detalles para esta venta.', 'error');
                    return;
                }

                let htmlRecibo = `
                    <div style="text-align:left; font-family: monospace; font-size: 14px; background: #f8fafc; padding: 20px; border: 1px dashed #cbd5e1; border-radius: 5px;">
                        <div style="text-align:center; margin-bottom: 15px; border-bottom: 1px dashed #94a3b8; padding-bottom: 10px;">
                            <strong style="font-size: 18px;">MEDISTOCK FARMACIA</strong><br>
                            Recibo N° ${String(id_venta).padStart(5, '0')}<br>
                        </div>
                        <table style="width:100%; border-collapse: collapse; margin-bottom: 15px;">
                            <tr style="border-bottom: 1px solid #cbd5e1; font-weight:bold;">
                                <td style="padding-bottom:5px;">Cant.</td>
                                <td style="padding-bottom:5px;">Producto</td>
                                <td style="text-align:right; padding-bottom:5px;">Monto</td>
                            </tr>
                `;
                
                data.detalles.forEach(item => {
                    // SEGURIDAD: Escapamos el nombre del producto que viene de la BD
                    htmlRecibo += `
                        <tr>
                            <td style="padding: 5px 0;">${escapeHTML(item.cantidad)}</td>
                            <td style="padding: 5px 0;">${escapeHTML(item.nombre_producto)}</td>
                            <td style="text-align:right; padding: 5px 0;">$${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });

                htmlRecibo += `
                        </table>
                        <div style="text-align:right; border-top: 1px dashed #94a3b8; padding-top: 10px; font-size: 16px;">
                            <strong>TOTAL PAGADO: $${parseFloat(data.total).toFixed(2)}</strong>
                        </div>
                    </div>
                `;

                Swal.fire({
                    title: 'Detalle de Facturación',
                    html: htmlRecibo,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '400px'
                });
            })
            .catch(err => Swal.fire('Error', 'Problema de conexión con el servidor', 'error'));
        }
    </script>
</body>
</html>