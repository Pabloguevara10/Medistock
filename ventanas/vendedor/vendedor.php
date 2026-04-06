<?php
session_start();
require '../login/php/conexion.php';

// Seguridad: Solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

// Lógica para cambiar de cliente
if (isset($_GET['cambiar_cliente'])) {
    unset($_SESSION['cliente_actual']);
    $_SESSION['carrito'] = [];
    header("Location: vendedor.php");
    exit();
}

// Inicializar carrito
if (!isset($_SESSION['carrito'])) { $_SESSION['carrito'] = []; }

// Lógica de agregar al carrito (Solo si hay cliente seleccionado)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto']) && isset($_SESSION['cliente_actual'])) {
    $id = intval($_POST['id_prod']);
    $nombre = $_POST['nombre_prod'];
    $precio = floatval($_POST['precio_prod']);
    $cantidad = intval($_POST['cantidad_prod']);
    $stock_actual = intval($_POST['stock_prod']);

    if ($cantidad > 0 && $cantidad <= $stock_actual) {
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] == $id) {
                if (($item['cantidad'] + $cantidad) <= $stock_actual) { $item['cantidad'] += $cantidad; }
                $encontrado = true; break;
            }
        }
        if (!$encontrado) {
            $_SESSION['carrito'][] = ['id' => $id, 'nombre' => $nombre, 'precio' => $precio, 'cantidad' => $cantidad];
        }
    }
    header("Location: vendedor.php"); exit();
}

// Lógica para vaciar carrito
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: vendedor.php");
    exit();
}

// Calcular Total del Carrito
$total_venta = 0;
foreach ($_SESSION['carrito'] as $item) { $total_venta += $item['precio'] * $item['cantidad']; }

// Lógica de búsqueda de productos
$busqueda = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$query_prod = "SELECT * FROM productos WHERE stock > 0";
if ($busqueda !== '') {
    $query_prod .= " AND (nombre LIKE '%$busqueda%' OR codigo LIKE '%$busqueda%')";
}
$query_prod .= " ORDER BY nombre ASC";
$productos = $conn->query($query_prod);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>MediStock - Punto de Venta</title>
  <link rel="stylesheet" href="../../css/globals.css" /> 
  <link rel="stylesheet" href="../../css/style.css" />
  <style>
    /* Estilos mejorados para que no se rompa el diseño */
    .step-container { 
        display: flex; flex-direction: column; align-items: center; justify-content: center; 
        background: white; border-radius: 12px; border: 1px solid #e2e8f0; 
        padding: 60px 20px; margin-top: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: center;
    }
    .client-bar { 
        background: #e8f5e9; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; 
        display: flex; justify-content: space-between; align-items: center; border: 1px solid #bbf7d0; 
    }
    .pos-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;}
    .product-list { 
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; 
        max-height: 65vh; overflow-y: auto; padding-right: 5px;
    }
    .pos-card { 
        background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; 
        text-align: center; display: flex; flex-direction: column; justify-content: space-between;
    }
    .cart-panel { 
        background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
        height: 72vh; display: flex; flex-direction: column; border: 1px solid #e2e8f0;
    }
    /* Scrollbar estilizada para la lista de productos */
    .product-list::-webkit-scrollbar { width: 6px; }
    .product-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px;}
    .product-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px;}
  </style>
</head>
<body>
  <div class="app-container">
    
    <header class="header">
      <div class="header-left">
        <img class="logo" src="../../img/logo.png" alt="Logo" />
        <img class="texto" src="../../img/tipografia.png" alt="MediStock" />
      </div>
      <div class="header-user">
        <div class="user-avatar"><?php echo substr($_SESSION['rol'], 0, 1); ?></div>
        <span><?php echo $_SESSION['nombre_completo']; ?></span>
      </div>
    </header>

    <aside class="sidebar">
      <div class="sidebar-title">MENÚ</div>
      
      <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
          <a href="../menuprincipal/menu.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
            <img src="../../img/home.png" alt="" /> <span>Inicio</span>
          </a>
          <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
            <img src="../../img/inventory.png" alt="" /> <span>Inventario</span>
          </a>
          <a href="../proovedores/proovedores.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
            <img src="../../img/cargamento.png" alt="" /> <span>Proveedores</span>
          </a>
      <?php endif; ?>

      <div class="sidebar-item active">
        <img src="../../img/pastilla.png" alt="" /> <span>Punto de Venta</span>
      </div>
      <a href="../clientes/clientes.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/usuario.png" alt="" /> <span>Clientes</span>
      </a>
      <a href="../login/login.php" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;">
        <img src="../../img/salir.png" alt="" /> <span>Salir</span>
      </a>
    </aside>

    <main class="main-content">
      
      <?php if (!isset($_SESSION['cliente_actual'])): ?>
        <h1 class="page-title">Punto de Venta</h1>
        
        <div class="step-container">
            <img src="../../img/usuario.png" style="width: 80px; margin-bottom: 20px; opacity: 0.6;">
            <h2 style="color: #1e293b; margin-bottom: 10px;">Identificación del Cliente</h2>
            <p style="color: #64748b; margin-bottom: 30px; font-size: 15px;">Por favor, ingresa la cédula del cliente para iniciar el proceso de facturación.</p>
            
            <form onsubmit="buscarCliente(event)" style="display: flex; gap: 10px; width: 100%; max-width: 450px;">
                <input type="text" id="id_busqueda" class="input-pill full-width" placeholder="Ejemplo: 12345678" style="margin:0;" required>
                <button type="submit" class="btn-submit-pill" style="width: auto; background-color: #3b7d85;">Siguiente</button>
            </form>
        </div>

      <?php else: ?>
        
        <div class="client-bar">
            <div>
                <span style="color: #166534; font-size: 14px;">Atendiendo a:</span>
                <strong style="color: #1e293b; margin-left: 5px; font-size: 15px;"><?php echo $_SESSION['cliente_actual']['nombre'] . " " . $_SESSION['cliente_actual']['apellido']; ?></strong>
                <span style="color: #64748b; font-size: 13px; margin-left: 10px;">(V-<?php echo $_SESSION['cliente_actual']['cedula']; ?>)</span>
            </div>
            <a href="vendedor.php?cambiar_cliente=1" style="color: #ef4444; font-size: 13px; font-weight: bold; text-decoration: none;">Cambiar Cliente 🔄</a>
        </div>

        <div class="pos-grid">
            <div class="dashboard-main-area" style="padding: 0; background: transparent; box-shadow: none;">
                
                <form method="GET" action="vendedor.php" style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="text" name="q" class="input-pill full-width" placeholder="Buscar medicamento o insumo..." value="<?php echo htmlspecialchars($busqueda); ?>" style="margin:0;">
                    <button type="submit" class="btn-submit-pill" style="width: auto; background-color:#3b7d85;">Buscar</button>
                    <?php if($busqueda): ?>
                        <a href="vendedor.php" class="btn-submit-pill" style="width: auto; background-color:#64748b; text-decoration:none; display:flex; align-items:center;">Limpiar</a>
                    <?php endif; ?>
                </form>

                <div class="product-list">
                    <?php if ($productos && $productos->num_rows > 0): ?>
                        <?php while($p = $productos->fetch_assoc()): ?>
                            <div class="pos-card">
                                <div>
                                    <h3 style="font-size: 14px; color:#1e293b; margin-bottom:5px;"><?php echo htmlspecialchars($p['nombre']); ?></h3>
                                    <p style="font-size: 12px; color:#64748b; margin-bottom:10px;"><?php echo htmlspecialchars($p['presentacion']); ?></p>
                                    <span style="color: #3b9b4a; font-weight: bold; font-size:16px;">$<?php echo number_format($p['precio_venta'], 2); ?></span>
                                    <div style="font-size: 11px; color:#94a3b8; margin-top:5px;">Stock: <?php echo $p['stock']; ?> uds.</div>
                                </div>
                                <form method="POST" style="margin-top: 15px; display: flex; gap: 5px; align-items: center;">
                                    <input type="hidden" name="id_prod" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="nombre_prod" value="<?php echo htmlspecialchars($p['nombre']); ?>">
                                    <input type="hidden" name="precio_prod" value="<?php echo $p['precio_venta']; ?>">
                                    <input type="hidden" name="stock_prod" value="<?php echo $p['stock']; ?>">
                                    
                                    <input type="number" name="cantidad_prod" value="1" min="1" max="<?php echo $p['stock']; ?>" class="input-pill" style="width: 55px; text-align: center; padding: 5px;">
                                    <button type="submit" name="agregar_producto" class="btn-submit-pill" style="font-size: 13px; padding: 5px 10px; flex-grow: 1;">Añadir</button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="grid-column: 1 / -1; text-align:center; color:#64748b; margin-top:20px;">No se encontraron productos disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="cart-panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; border-bottom: 2px solid #f1f5f9; padding-bottom:10px;">
                    <h3 style="margin:0; color:#334155;">Facturación</h3>
                    <?php if(!empty($_SESSION['carrito'])): ?>
                        <a href="vendedor.php?vaciar=1" style="color:#ef4444; text-decoration:none; font-size:13px; font-weight:bold;">Vaciar 🗑️</a>
                    <?php endif; ?>
                </div>

                <div style="flex-grow: 1; overflow-y: auto; padding-right: 5px;">
                    <?php if (empty($_SESSION['carrito'])): ?>
                        <p style="text-align:center; color:#94a3b8; margin-top: 50px; font-style:italic;">Añade productos para cobrar</p>
                    <?php else: ?>
                        <?php foreach($_SESSION['carrito'] as $i): ?>
                            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                <span style="font-weight:600; color:#1e293b;"><?php echo $i['cantidad']; ?>x <?php echo htmlspecialchars($i['nombre']); ?></span>
                                <strong style="color:#3b7d85;">$<?php echo number_format($i['precio'] * $i['cantidad'], 2); ?></strong>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px dashed #cbd5e1;">
                    <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #0f172a;">
                        <span>Total:</span> <span>$<?php echo number_format($total_venta, 2); ?></span>
                    </div>
                    
                    <form id="form-venta-final" action="php/procesar_venta.php" method="POST" style="display:none;"></form>

                    <button type="button" onclick="procesarVenta()" class="btn-submit-pill full-width" style="margin-top: 15px; background: #f59e0b; font-size: 15px;" <?php echo empty($_SESSION['carrito']) ? 'disabled' : ''; ?>>
                        Cobrar Venta
                    </button>
                </div>
            </div>
        </div>
      <?php endif; ?>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // --- LÓGICA DE ALERTAS Y REDIRECCIONES ---
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const factura = urlParams.get('factura');

        if (status === 'venta_exitosa') {
            Swal.fire({
              title: '¡Venta Exitosa!',
              html: `<p>La transacción se completó correctamente.</p>
                     <div style="background:#f1f5f9; padding:15px; border-radius:8px; margin-top:10px; border: 1px solid #e2e8f0;">
                        <span style="font-size:13px; color:#64748b;">NÚMERO DE RECIBO</span><br>
                        <strong style="font-size:20px; color:#3b7d85;">#${factura}</strong>
                     </div>`,
              icon: 'success',
              confirmButtonColor: '#3b9b4a',
              confirmButtonText: 'Aceptar'
            });
            window.history.replaceState(null, null, window.location.pathname);
            
        } else if (status === 'error_db') {
            Swal.fire('Error del Sistema', 'No se pudo procesar la venta. Intenta nuevamente.', 'error');
            window.history.replaceState(null, null, window.location.pathname);
        }
    });

    // --- BUSCADOR Y REGISTRO DE CLIENTE (PASO 1) ---
    function buscarCliente(e) {
        if(e) e.preventDefault(); // Evita recargar la página al presionar Enter

        const cedula = document.getElementById('id_busqueda').value.trim();
        if (cedula === '') return;

        fetch('php/buscar_cliente.php?cedula=' + encodeURIComponent(cedula))
        .then(response => response.json())
        .then(data => {
            if (data.encontrado) {
                // Cliente encontrado, recargamos para entrar al Paso 2
                location.reload();
            } else {
                // Cliente no existe, abrimos Modal de Registro
                Swal.fire({
                    title: 'Nuevo Cliente',
                    html: `
                        <p style="font-size:13px; color:#64748b; margin-bottom:15px;">La cédula <b>${cedula}</b> no está registrada. Completa sus datos:</p>
                        <div style="text-align: left; padding: 0 10px;">
                            <input id="swal-nombre" class="swal2-input" placeholder="Nombres" style="margin: 5px 0 10px 0; width: 100%; box-sizing: border-box;" required>
                            <input id="swal-apellido" class="swal2-input" placeholder="Apellidos" style="margin: 5px 0 10px 0; width: 100%; box-sizing: border-box;" required>
                            <input id="swal-email" type="email" class="swal2-input" placeholder="Correo Electrónico" style="margin: 5px 0 10px 0; width: 100%; box-sizing: border-box;">
                            <input id="swal-tlf" class="swal2-input" placeholder="Teléfono" style="margin: 5px 0 10px 0; width: 100%; box-sizing: border-box;" required>
                            <input id="swal-dir" class="swal2-input" placeholder="Dirección (Opcional)" style="margin: 5px 0 10px 0; width: 100%; box-sizing: border-box;">
                        </div>
                    `,
                    focusConfirm: false,
                    confirmButtonText: 'Registrar y Continuar',
                    confirmButtonColor: '#3b7d85',
                    preConfirm: () => {
                        const nombre = document.getElementById('swal-nombre').value.trim();
                        const apellido = document.getElementById('swal-apellido').value.trim();
                        const email = document.getElementById('swal-email').value.trim();
                        const tlf = document.getElementById('swal-tlf').value.trim();
                        const dir = document.getElementById('swal-dir').value.trim();

                        if (!nombre || !apellido || !tlf) {
                            Swal.showValidationMessage('Nombres, apellidos y teléfono son obligatorios.');
                            return false;
                        }
                        return { cedula, nombre, apellido, email, tlf, dir };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        registrarCliente(result.value);
                    }
                });
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // --- GUARDAR NUEVO CLIENTE AJAX ---
    function registrarCliente(datos) {
        fetch('php/registrar_cliente_ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) { 
                location.reload(); // Recarga para entrar al Paso 2
            } else { 
                Swal.fire('Error', 'Hubo un problema registrando al cliente.', 'error'); 
            }
        });
    }

    // --- PROCESAR LA VENTA (CORRECCIÓN DEL BUG) ---
    function procesarVenta() {
        Swal.fire({
            title: '¿Confirmar cobro?',
            text: "Se generará la factura y se descontarán los productos del inventario.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, cobrar ahora',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ahora sí enviamos un POST usando el formulario oculto
                document.getElementById('form-venta-final').submit();
            }
        });
    }
  </script>
</body>
</html>