<?php
session_start();
// Validación estricta de rol
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: ../login/login.php?status=error_acceso");
    exit();
}

require '../login/php/conexion.php'; 

// 1. Obtenemos los proveedores de la base de datos
$query_proveedores = "SELECT * FROM proveedores ORDER BY id DESC";
$resultado_proveedores = $conn->query($query_proveedores);

// 2. Obtenemos el inventario de productos (Incluyendo PRECIO DE COMPRA para los cálculos)
$query_productos = "SELECT id, nombre, categoria, presentacion, stock, precio_compra FROM productos";
$resultado_productos = $conn->query($query_productos);
$inventario_productos = [];
if($resultado_productos) {
    while($p = $resultado_productos->fetch_assoc()) {
        $inventario_productos[] = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <title>MediStock - Proveedores</title>
  <link rel="stylesheet" href="../../css/globals.css" /> 
  <link rel="stylesheet" href="../../css/style.css" />
  
  <style>
    .providers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .provider-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      display: flex;
      flex-direction: column;
      gap: 12px;
      border-left: 5px solid #3b7d85;
    }
    .provider-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 10px;
    }
    .provider-name { font-size: 18px; font-weight: bold; color: #1e293b; margin: 0; }
    .provider-rif { font-size: 12px; color: #64748b; margin-top: 3px; display: block; }
    .provider-categories { font-size: 13px; color: #3b9b4a; background: #e8f5e9; padding: 4px 10px; border-radius: 12px; display: inline-block; font-weight: 600;}
    .provider-info { font-size: 14px; color: #475569; display: flex; align-items: center; gap: 8px; }
    .provider-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
      padding-top: 15px;
    }
    .btn-pedido {
      background: #3b7d85; 
      color: white; 
      border: none; 
      padding: 8px 16px; 
      border-radius: 20px; 
      cursor: pointer; 
      font-size: 13px; 
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .btn-pedido:hover { background: #2c5e64; }
    .icon-actions img { width: 18px; cursor: pointer; margin-left: 12px; opacity: 0.7; transition: opacity 0.3s; }
    .icon-actions img:hover { opacity: 1; }

    /* Estilos específicos para la lista de pedido */
    .pedido-row {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 13px;
    }
    .pedido-header-row {
      font-weight: bold;
      background: #f8fafc;
      color: #64748b;
      border-radius: 5px;
    }
    .total-box {
      background: #1e293b;
      color: white;
      padding: 15px;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="app-container">
    <header class="header">
      <div class="header-left">
        <img class="logo" src="../../img/logo.png" alt="Logo" />
        <img class="texto" src="../../img/tipografia.png" alt="MediStock" />
      </div>
      <div class="header-search">
        <input type="text" id="buscador-proveedor" placeholder="Buscar proveedores..." />
        <img src="../../img/buscar.png" alt="Buscar" />
      </div>
      <div class="header-user">
        <div class="user-avatar">Ad</div>
        <span>Administrador</span>
      </div>
    </header>

    <aside class="sidebar">
      <div class="sidebar-title">MENÚ PRINCIPAL</div>
      <a href="../menuprincipal/menu.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/home.png" alt="" /> <span>Inicio</span>
      </a>
      <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/inventory.png" alt="" /> <span>Inventario</span>
      </a>
      <a href="../reportes/reportes.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/report.png" alt="" /> <span>Reportes</span>
      </a>
      <div class="sidebar-item active">
        <img src="../../img/cargamento.png" alt="" /> <span>Proveedores</span>
      </div>
      <a href="../empleados/empleados.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/empleados.png" alt="" /> <span>Empleados</span>
      </a>
      <a href="../login/login.php" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;">
        <img src="../../img/salir.png" alt="" /> <span>Salir</span>
      </a>
    </aside>

    <main class="main-content">
      <h1 class="page-title">Gestión de Proveedores</h1>

      <div class="top-widgets" style="grid-template-columns: 1fr;">
        <div class="alerts-panel" style="width: 100%;">
          <div class="sidebar-title" style="margin:0; padding-bottom: 10px;">ALERTAS DE DESPACHO</div>
          <div class='alert-box' style='background-color: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px; font-size: 14px; margin-top: 10px; border-radius: 6px;'>
            ✅ <span style="font-weight: bold; color: #047857;">Laboratorios Leti:</span> Pedido #405 entregado con éxito.
          </div>
        </div>
      </div>

      <div class="table-section" style="margin-top: 20px; background: transparent; box-shadow: none; padding: 0;">
        <div class="table-toolbar" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
          <div class="toolbar-left">
            <h2 style="font-size: 16px; margin:0; color: #334155;">Directorio Activo</h2>
          </div>
          <button class="add-btn" id="openProviderModalBtn">
            <img src="../../img/plus.png" alt="" /> Nuevo Proveedor
          </button>
        </div>

        <div class="providers-grid">
          <?php
          if ($resultado_proveedores && $resultado_proveedores->num_rows > 0) {
              while($row = $resultado_proveedores->fetch_assoc()) {
                  $datos_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
          ?>
                <div class="provider-card">
                  <div class="provider-header">
                    <div>
                      <h3 class="provider-name"><?php echo htmlspecialchars($row['nombre']); ?></h3>
                      <span class="provider-rif"><strong>RIF:</strong> <?php echo htmlspecialchars($row['rif']); ?></span>
                    </div>
                    <div class="icon-actions">
                      <img src="../../img/edit.png" alt="Editar" title="Editar" style="cursor:pointer;" onclick='abrirModalEditarProveedor(<?php echo $datos_json; ?>)'>
                      <img src="../../img/borrar.png" alt="Eliminar" title="Eliminar" style="cursor:pointer;" onclick="eliminarProveedor(<?php echo $row['id']; ?>)">
                    </div>
                  </div>
                  <div>
                    <span class="provider-categories"><?php echo htmlspecialchars($row['categorias']); ?></span>
                  </div>
                  <div class="provider-info">
                     <img src="../../img/tlf.png" alt="Teléfono" style="width:16px;"> <?php echo htmlspecialchars($row['telefono']); ?>
                  </div>
                  <div class="provider-info">
                     <img src="../../img/email.png" alt="Email" style="width:16px;"> <?php echo htmlspecialchars($row['correo']); ?>
                  </div>
                  <div class="provider-info">
                     <img src="../../img/ubi.png" alt="Ubicación" style="width:16px;"> <?php echo htmlspecialchars($row['direccion']); ?>
                  </div>
                  <div class="provider-actions">
                    <button type="button" class="btn-pedido" onclick='abrirModalPedido(<?php echo $datos_json; ?>)'>Generar Pedido</button>
                  </div>
                </div>
          <?php
              }
          } else {
              echo "<p style='grid-column: 1 / -1; text-align: center; color: #64748b; padding: 20px;'>No hay proveedores registrados aún.</p>";
          }
          ?>
        </div>
      </div>
    </main>
  </div>

  <div class="modal-overlay" id="addProviderModal">
    <div class="modal-content new-style-modal">
      <span class="close-btn" id="closeProviderModalBtn">&times;</span>
      <div class="modal-header-centered">
        <h2>NUEVO PROVEEDOR</h2>
        <img src="../../img/logo.png" alt="Logo" class="modal-logo" /> 
      </div>
      <form class="add-product-form-new" action="php/procesar_nuevo_proveedor.php" method="POST">
        <input type="text" name="nombre" class="input-pill full-width" placeholder="Nombre" required />
        <div class="row-2-cols">
          <input type="text" name="rif" class="input-pill" placeholder="RIF" required />
          <input type="text" name="telefono" class="input-pill" placeholder="Teléfono" required />
        </div>
        <input type="email" name="correo" class="input-pill full-width" placeholder="Correo" required />
        <select name="categorias" class="input-pill full-width" required>
          <option value="" disabled selected>Categoría Principal</option>
          <option value="Antibióticos">Antibióticos</option>
          <option value="Analgésicos">Analgésicos</option>
          <option value="Alergias">Alergias</option>
          <option value="Gastrointestinal">Gastrointestinal</option>
          <option value="Insumos Médicos">Insumos Médicos</option>
          <option value="General / Multicategoría">General / Multicategoría</option>
        </select>
        <input type="text" name="direccion" class="input-pill full-width" placeholder="Dirección" required />
        <div class="submit-container"><button type="submit" class="btn-submit-pill">Registrar</button></div>
      </form>
    </div>
  </div> 

  <div class="modal-overlay" id="editProviderModal">
    <div class="modal-content new-style-modal">
      <span class="close-btn" id="closeEditProviderModalBtn">&times;</span>
      <div class="modal-header-centered">
        <h2>EDITAR PROVEEDOR</h2>
        <img src="../../img/logo.png" alt="Logo" class="modal-logo" /> 
      </div>
      <form class="add-product-form-new" action="php/actualizar_proveedor.php" method="POST">
        <input type="hidden" name="id" id="edit_id" />
        <input type="text" name="nombre" id="edit_nombre" class="input-pill full-width" required />
        <div class="row-2-cols">
          <input type="text" name="rif" id="edit_rif" class="input-pill" required />
          <input type="text" name="telefono" id="edit_telefono" class="input-pill" required />
        </div>
        <input type="email" name="correo" id="edit_correo" class="input-pill full-width" required />
        <select name="categorias" id="edit_categorias" class="input-pill full-width" required>
          <option value="Antibióticos">Antibióticos</option>
          <option value="Analgésicos">Analgésicos</option>
          <option value="Alergias">Alergias</option>
          <option value="Gastrointestinal">Gastrointestinal</option>
          <option value="Insumos Médicos">Insumos Médicos</option>
          <option value="General / Multicategoría">General / Multicategoría</option>
        </select>
        <input type="text" name="direccion" id="edit_direccion" class="input-pill full-width" required />
        <div class="submit-container"><button type="submit" class="btn-submit-pill" style="background-color: #f59e0b;">Guardar Cambios</button></div>
      </form>
    </div>
  </div>

  <div class="modal-overlay" id="pedidoModal">
    <div class="modal-content new-style-modal" style="max-width: 700px;">
      <span class="close-btn" id="closePedidoModalBtn">&times;</span>
      
      <div class="modal-header-centered" style="margin-bottom: 10px;">
        <h2 id="pedidoModalTitle" style="font-size: 18px; color: #1e293b; margin:0;">GENERAR PEDIDO</h2>
        <p id="pedidoModalSubtitle" style="color:#3b9b4a; font-size:13px; font-weight: bold; background: #e8f5e9; padding: 4px 10px; border-radius: 12px; display: inline-block; margin-top: 8px;"></p>
      </div>

      <form id="formGenerarPedido" action="php/procesar_pedido.php" method="POST">
        <input type="hidden" name="proveedor_id" id="pedido_proveedor_id" />
        
        <div class="pedido-row pedido-header-row">
            <div>Producto / Stock</div>
            <div style="text-align:center;">Precio Unit.</div>
            <div style="text-align:center;">Cantidad</div>
            <div style="text-align:right;">Subtotal</div>
        </div>

        <div id="listaProductosPedido" style="max-height: 350px; overflow-y: auto; margin: 5px 0; border: 1px solid #e2e8f0; border-radius: 8px;">
            </div>

        <div class="total-box">
            <span style="font-weight: bold; font-size: 16px;">TOTAL ESTIMADO DEL PEDIDO:</span>
            <span id="pedidoTotalFinal" style="font-size: 20px; font-weight: bold;">$0.00</span>
        </div>

        <div class="submit-container" style="margin-top: 20px;">
          <button type="submit" class="btn-submit-pill" style="background-color: #3b7d85;">Confirmar Recepción de Pedido</button>
        </div>
      </form>
    </div>
  </div> 

  <script src="../../fonds/sweetalert.cjs"></script>
  <script>
    // 1. BUSCADOR
    document.addEventListener("DOMContentLoaded", function() {
      const searchInput = document.getElementById('buscador-proveedor');
      const providerCards = document.querySelectorAll('.provider-card');
      searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        providerCards.forEach(card => {
          if (card.textContent.toLowerCase().includes(searchTerm)) card.style.display = 'flex'; 
          else card.style.display = 'none';
        });
      });
    });

    // 2. MODALES
    const addModal = document.getElementById('addProviderModal');
    const editModal = document.getElementById('editProviderModal');
    const pedidoModal = document.getElementById('pedidoModal');

    document.getElementById('openProviderModalBtn').onclick = () => addModal.classList.add('active');
    document.getElementById('closeProviderModalBtn').onclick = () => addModal.classList.remove('active');
    document.getElementById('closeEditProviderModalBtn').onclick = () => editModal.classList.remove('active');
    document.getElementById('closePedidoModalBtn').onclick = () => pedidoModal.classList.remove('active');

    window.onclick = (e) => {
      if (e.target == addModal) addModal.classList.remove('active');
      if (e.target == editModal) editModal.classList.remove('active');
      if (e.target == pedidoModal) pedidoModal.classList.remove('active');
    }

    // 3. FUNCIONES DE DATOS
    function abrirModalEditarProveedor(p) {
      document.getElementById('edit_id').value = p.id;
      document.getElementById('edit_nombre').value = p.nombre;
      document.getElementById('edit_rif').value = p.rif;
      document.getElementById('edit_telefono').value = p.telefono;
      document.getElementById('edit_correo').value = p.correo;
      document.getElementById('edit_categorias').value = p.categorias;
      document.getElementById('edit_direccion').value = p.direccion;
      editModal.classList.add('active');
    }

    const todosLosProductos = <?php echo json_encode($inventario_productos); ?>;

    function abrirModalPedido(proveedor) {
      document.getElementById('pedidoModalTitle').innerText = 'PEDIDO: ' + proveedor.nombre.toUpperCase();
      document.getElementById('pedidoModalSubtitle').innerText = proveedor.categorias;
      document.getElementById('pedido_proveedor_id').value = proveedor.id;

      const lista = document.getElementById('listaProductosPedido');
      lista.innerHTML = '';
      document.getElementById('pedidoTotalFinal').innerText = '$0.00';

      let filtrados = todosLosProductos;
      if (proveedor.categorias !== 'General / Multicategoría') {
          filtrados = todosLosProductos.filter(p => p.categoria === proveedor.categorias);
      }

      if (filtrados.length === 0) {
          lista.innerHTML = '<p style="text-align:center; color:#64748b; padding:20px;">No hay productos en esta categoría.</p>';
      } else {
          filtrados.forEach(prod => {
              const stockCritico = prod.stock <= 10;
              const row = document.createElement('div');
              row.className = 'pedido-row';
              row.innerHTML = `
                  <div>
                      <strong style="color:#1e293b;">${prod.nombre}</strong><br>
                      <span style="font-size:11px; color:${stockCritico ? '#d33' : '#64748b'}; font-weight:600;">
                        Inv: ${prod.stock} uds. ${stockCritico ? '(BAJO)' : ''}
                      </span>
                  </div>
                  <div style="text-align:center;">$${parseFloat(prod.precio_compra).toFixed(2)}</div>
                  <div style="text-align:center;">
                      <input type="number" name="cantidades[${prod.id}]" 
                             class="input-pill qty-input" 
                             style="width:70px; padding: 5px; text-align:center;" 
                             min="0" placeholder="0" 
                             data-price="${prod.precio_compra}">
                  </div>
                  <div style="text-align:right; font-weight:bold;" class="subtotal-item">$0.00</div>
              `;
              lista.appendChild(row);
          });

          // Agregar evento de cálculo
          const inputs = lista.querySelectorAll('.qty-input');
          inputs.forEach(input => {
              input.addEventListener('input', calcularTotalPedido);
          });
      }
      pedidoModal.classList.add('active');
    }

    function calcularTotalPedido() {
        let totalGeneral = 0;
        const rows = document.querySelectorAll('.pedido-row:not(.pedido-header-row)');
        
        rows.forEach(row => {
            const input = row.querySelector('.qty-input');
            const subtotalEl = row.querySelector('.subtotal-item');
            const precio = parseFloat(input.dataset.price);
            const cantidad = parseInt(input.value) || 0;
            
            const subtotal = precio * cantidad;
            subtotalEl.innerText = '$' + subtotal.toFixed(2);
            totalGeneral += subtotal;
        });

        document.getElementById('pedidoTotalFinal').innerText = '$' + totalGeneral.toFixed(2);
    }

    // 4. ALERTAS
    document.addEventListener("DOMContentLoaded", function() {
      const status = new URLSearchParams(window.location.search).get('status');
      if (status) {
        let config = { icon: 'success', confirmButtonColor: '#3b9b4a' };
        if (status === 'added') { config.title = '¡Registrado!'; config.text = 'Proveedor añadido.'; }
        else if (status === 'updated') { config.title = '¡Actualizado!'; config.text = 'Cambios guardados.'; config.confirmButtonColor = '#f59e0b'; }
        else if (status === 'deleted') { config.title = '¡Eliminado!'; config.text = 'Proveedor borrado.'; config.confirmButtonColor = '#64748b'; }
        else if (status === 'ordered') { config.title = '¡Pedido Exitoso!'; config.text = 'Stock actualizado.'; config.confirmButtonColor = '#3b7d85'; }
        else if (status.includes('error')) { config.title = '¡Error!'; config.text = 'Ocurrió un problema.'; config.icon = 'error'; config.confirmButtonColor = '#d33'; }
        
        Swal.fire(config);
        window.history.replaceState(null, null, window.location.pathname);
      }
    });

    function eliminarProveedor(id) {
      Swal.fire({
        title: '¿Eliminar?', text: "No podrás deshacer esto.", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, eliminar'
      }).then((r) => { if (r.isConfirmed) window.location.href = "php/eliminar_proveedor.php?id=" + id; });
    }
  </script>
</body>
</html>