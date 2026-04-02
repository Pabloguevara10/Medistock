<?php
session_start();
// Asegúrate de que esta ruta sea la que te funcionó en el paso anterior
require '../login/php/conexion.php'; 

// --- 1. Total de productos ---
$res_total = $conn->query("SELECT COUNT(*) as total FROM productos");
$total_productos = $res_total->fetch_assoc()['total'];

// --- 2. Valor del Inventario (Calculado sumando: stock * precio de compra) ---
$res_valor = $conn->query("SELECT SUM(stock * precio_compra) as valor FROM productos");
$valor_inventario = $res_valor->fetch_assoc()['valor'];
$valor_inventario = $valor_inventario ? $valor_inventario : 0; // Si no hay productos, es 0

// --- 3. Stock Crítico (Contamos los que tienen stock menor o igual a 10, o están vencidos) ---
$res_critico = $conn->query("SELECT COUNT(*) as total_critico FROM productos WHERE stock <= 10 OR estado = 'Vencido'");
$total_critico = $res_critico->fetch_assoc()['total_critico'];

// --- 4. Alertas (Traemos hasta 3 productos que estén críticos o vencidos para mostrarlos en la caja) ---
$alertas = $conn->query("SELECT nombre, stock, estado FROM productos WHERE stock <= 10 OR estado = 'Vencido' ORDER BY stock ASC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <title>MediStock - Inventario Admin</title>
  <link rel="stylesheet" href="css/globals.css" /> <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="app-container">
    
    <header class="header">
      <div class="header-left">
        <img class="logo" src="img/logo.png" alt="Logo" />
        <img class="texto" src="img/tipografia.png" alt="MediStock" />
      </div>
      <div class="header-search">
        <input type="text" placeholder="Buscar medicamentos, proveedores..." />
        <img src="img/buscar.png" alt="Buscar" />
      </div>
      <div class="header-user">
        <div class="user-avatar">Ad</div>
        <span>Administrador</span>
      </div>
    </header>

    <aside class="sidebar">
      <div class="sidebar-title">MENÚ PRINCIPAL</div>
      <div class="sidebar-item">
        <img src="img/vector.png" alt="" /> <span>Inicio</span>
      </div>
      <div class="sidebar-item active">
        <img src="img/vector (1).png" alt="" /> <span>Inventario</span>
      </div>
      <div class="sidebar-item">
        <img src="img/vector (2).png" alt="" /> <span>Reportes</span>
      </div>
      <div class="sidebar-item">
        <img src="img/vector (3).png" alt="" /> <span>Proveedores</span>
      </div>
      <div class="sidebar-item">
        <img src="img/vector (4).png" alt="" /> <span>Empleados</span>
      </div>
      <div class="sidebar-item logout-btn">
        <img src="img/salir.png" alt="" /> <span>Salir</span>
      </div>
    </aside>

    <main class="main-content">
      <h1 class="page-title">Gestión de Inventario</h1>

      <div class="top-widgets">
        <div class="summary-cards">
          <div class="card blue">
            <div class="card-title">Total Productos</div>
            <div class="card-value"><?php echo $total_productos; ?></div>
          </div>
          <div class="card green">
            <div class="card-title">Valor Inventario</div>
            <div class="card-value">$<?php echo number_format($valor_inventario, 2); ?></div>
          </div>
          <div class="card red">
            <div class="card-title" style="color:white;">Stock Crítico</div>
            <div class="card-value" style="color:white;"><?php echo $total_critico; ?></div>
          </div>
        </div>
        
        <div class="alerts-panel">
          <div class="sidebar-title" style="margin:0; padding-bottom: 10px;">ALERTAS SISTEMA</div>
          
          <?php
          // Verificamos si hay alertas que mostrar
          if ($alertas->num_rows > 0) {
              while($alerta = $alertas->fetch_assoc()) {
                  // Cambiamos el mensaje dependiendo de si está vencido o solo tiene poco stock
                  if ($alerta['estado'] == 'Vencido') {
                      $mensaje = "🚨 <b>¡Vencido!</b> " . htmlspecialchars($alerta['nombre']);
                  } else {
                      $mensaje = "⚠️ <b>Stock bajo:</b> " . htmlspecialchars($alerta['nombre']) . " (Quedan " . $alerta['stock'] . ")";
                  }
                  echo "<div class='alert-box' style='padding: 8px 12px; font-size: 14px; margin-top: 8px;'>" . $mensaje . "</div>";
              }
          } else {
              // Si no hay productos críticos, mostramos un mensaje de "Todo bien" en color verde
              echo "<div class='alert-box' style='background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; font-size: 14px; margin-top: 10px;'>✅ Todo en orden. No hay alertas de inventario.</div>";
          }
          ?>
          
        </div>
      </div>

      <div class="table-section">
        <div class="table-toolbar">
          <div class="toolbar-left">
            <input type="text" class="search-input" placeholder="Buscar por código o nombre..." />
            <button class="filter-btn">Filtrar <img src="img/filtrar.png" alt="" style="vertical-align: middle; width: 14px;"></button>
          </div>
          <button class="add-btn" id="openModalBtn">
            <img src="img/plus.png" alt="" /> Nuevo Producto
          </button>
        </div>

        <div class="table-responsive">
          <table class="inventory-table">
            <thead>
              <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Presentación</th>
                <th>Stock</th>
                <th>Compra</th>
                <th>Venta</th>
                <th>Laboratorio</th>
                <th>F. Llegada</th>
                <th>F. Vencimiento</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Incluir conexión a la BD
                require '../login/php/conexion.php';

                // Consultar todos los productos
                $query = "SELECT * FROM productos ORDER BY id DESC";
                $resultado = $conn->query($query);

                // Si hay productos, los mostramos en la tabla
                if ($resultado->num_rows > 0) {
                    while($row = $resultado->fetch_assoc()) {
                        
                        // Determinar la clase de la píldora de color según el estado
                        $clase_estado = "";
                        if($row['estado'] == 'Óptimo') $clase_estado = 'optimo';
                        else if($row['estado'] == 'Crítico') $clase_estado = 'critico';
                        else if($row['estado'] == 'Vencido') $clase_estado = 'critico'; // Usamos rojo también para vencido

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['nombre']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['presentacion']) . "</td>";
                        
                        // Resaltar en negrita el stock si está en crítico
                        if ($row['estado'] == 'Crítico' || $row['estado'] == 'Vencido') {
                            echo "<td style='color:#d8333c;'><strong>" . $row['stock'] . "</strong></td>";
                        } else {
                            echo "<td><strong>" . $row['stock'] . "</strong></td>";
                        }
                        
                        // Formatear precios a dólares (ej: $4.50)
                        echo "<td>$" . number_format($row['precio_compra'], 2) . "</td>";
                        echo "<td>$" . number_format($row['precio_venta'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['laboratorio']) . "</td>";
                        
                        // Formatear fechas para que se vean como DD/MM/YYYY
                        echo "<td>" . date("d/m/Y", strtotime($row['fecha_llegada'])) . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row['fecha_vencimiento'])) . "</td>";
                        
                        echo "<td><span class='badge " . $clase_estado . "'>" . $row['estado'] . "</span></td>";
                        
                        // Convertimos todos los datos de esa fila en un formato que JavaScript entienda (JSON)
                        $datos_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                        echo "<td class='action-icons'>";
                        // Botón Editar: Llama a una función pasándole todos los datos
                        echo "<img src='img/edit.png' alt='Editar' title='Editar' style='cursor:pointer;' onclick='abrirModalEditar($datos_json)' />";
                        // Botón Eliminar: Llama a una función pasándole solo el ID
                        echo "<img src='img/borrar.png' alt='Eliminar' title='Eliminar' style='cursor:pointer;' onclick='eliminarProducto(" . $row['id'] . ")' />";
                        echo "</td>";
                    }
                } else {
                    // Si no hay productos en la base de datos
                    echo "<tr><td colspan='12' style='text-align:center; padding: 20px;'>No hay productos registrados en el inventario. Haz clic en 'Nuevo Producto' para comenzar.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <div class="modal-overlay" id="addProductModal">
    <div class="modal-content new-style-modal">
      
      <span class="close-btn" id="closeModalBtn">&times;</span>
      
      <div class="modal-header-centered">
        <h2>NUEVO PRODUCTO</h2>
        <img src="img/logo.png" alt="Logo" class="modal-logo" /> 
      </div>

      <form class="add-product-form-new" action="php/procesar_nuevo_producto.php" method="POST">
        
        <input type="text" name="nombre" class="input-pill full-width" placeholder="Nombre" required />
        
        <input type="text" name="codigo" class="input-pill full-width" placeholder="Código de Barras" required />
        
        <select name="categoria" class="input-pill full-width" required>
          <option value="" disabled selected>Categoría</option>
          <option value="Antibióticos">Antibióticos</option>
          <option value="Analgésicos">Analgésicos</option>
          <option value="Alergias">Alergias</option>
          <option value="Gastrointestinal">Gastrointestinal</option>
        </select>

        <div class="row-3-cols">
          <input type="number" name="stock" class="input-pill" placeholder="Stock" required />
          <input type="text" name="presentacion" class="input-pill" placeholder="Presentación" required />
          <input type="text" name="laboratorio" class="input-pill" placeholder="Laboratorio" />
        </div>

        <div class="row-2-cols">
          <input type="text" name="fecha_vencimiento" class="input-pill" placeholder="Fecha Vencimiento: DD/MM/AA" onfocus="(this.type='date')" onblur="(this.type='text')" required />
          <input type="text" name="fecha_llegada" class="input-pill" placeholder="Fecha Llegada: DD/MM/AA" onfocus="(this.type='date')" onblur="(this.type='text')" required />
        </div>

        <div class="row-2-cols">
          <input type="number" name="precio_compra" step="0.01" class="input-pill" placeholder="Precio de Compra" required />
          <input type="number" name="precio_venta" step="0.01" class="input-pill" placeholder="Precio de Venta" required />
        </div>

        <div class="submit-container">
          <button type="submit" class="btn-submit-pill">Añadir Producto</button>
        </div>

      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('addProductModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.addEventListener('click', () => modal.classList.add('active'));
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.remove('active');
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // 1. Seleccionamos el campo de búsqueda de la tabla y las filas de la tabla
      const searchInput = document.querySelector('.search-input');
      const tableRows = document.querySelectorAll('.inventory-table tbody tr');

      // 2. Escuchamos cada vez que el usuario teclea algo en el campo
      searchInput.addEventListener('keyup', function(e) {
        // Convertimos lo que el usuario escribió a minúsculas para que la búsqueda no sea sensible a mayúsculas
        const searchTerm = e.target.value.toLowerCase();

        // 3. Recorremos cada fila de la tabla
        tableRows.forEach(row => {
          // Extraemos todo el texto de la fila (Código, Nombre, Categoría, etc.) y lo pasamos a minúsculas
          const rowText = row.textContent.toLowerCase();

          // 4. Si el texto de la fila incluye lo que el usuario escribió, la mostramos. Si no, la ocultamos.
          if (rowText.includes(searchTerm)) {
            row.style.display = ''; // Muestra la fila (comportamiento por defecto)
          } else {
            row.style.display = 'none'; // Oculta la fila
          }
        });
      });
      
      // Opcional: Hacer que el botón "Filtrar" también ejecute la búsqueda por si el usuario le da clic
      const filterBtn = document.querySelector('.filter-btn');
      if(filterBtn) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Evita que el botón recargue la página si está dentro de un form
            // La búsqueda ya ocurre al teclear, pero este evento da retroalimentación visual si hacen clic
        });
      }
    });
  </script>

  <div class="modal-overlay" id="editProductModal">
    <div class="modal-content new-style-modal">
      <span class="close-btn" id="closeEditModalBtn">&times;</span>
      
      <div class="modal-header-centered">
        <h2>EDITAR PRODUCTO</h2>
        <img src="img/logo.png" alt="Logo" class="modal-logo" /> 
      </div>

      <form class="add-product-form-new" action="php/actualizar_producto.php" method="POST">
        <input type="hidden" name="id" id="edit_id" />
        
        <input type="text" name="nombre" id="edit_nombre" class="input-pill full-width" required />
        <input type="text" name="codigo" id="edit_codigo" class="input-pill full-width" required />
        
        <select name="categoria" id="edit_categoria" class="input-pill full-width" required>
          <option value="Antibióticos">Antibióticos</option>
          <option value="Analgésicos">Analgésicos</option>
          <option value="Alergias">Alergias</option>
          <option value="Gastrointestinal">Gastrointestinal</option>
        </select>

        <div class="row-3-cols">
          <input type="number" name="stock" id="edit_stock" class="input-pill" required />
          <input type="text" name="presentacion" id="edit_presentacion" class="input-pill" required />
          <input type="text" name="laboratorio" id="edit_laboratorio" class="input-pill" />
        </div>

        <div class="row-2-cols">
          <input type="date" name="fecha_vencimiento" id="edit_fecha_vencimiento" class="input-pill" required />
          <input type="date" name="fecha_llegada" id="edit_fecha_llegada" class="input-pill" required />
        </div>

        <div class="row-2-cols">
          <input type="number" name="precio_compra" id="edit_precio_compra" step="0.01" class="input-pill" required />
          <input type="number" name="precio_venta" id="edit_precio_venta" step="0.01" class="input-pill" required />
        </div>

        <div class="submit-container">
          <button type="submit" class="btn-submit-pill" style="background-color: #f59e0b;">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
  
  <script src="../../fonds/sweetalert.cjs"></script>
  <script>
    // --- LÓGICA PARA LEER LA URL Y MOSTRAR ÉXITOS ---
    document.addEventListener("DOMContentLoaded", function() {
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');

      if (status) {
        let titulo = '';
        let texto = '';
        let icono = 'success';

        if (status === 'added') {
          titulo = '¡Añadido!';
          texto = 'El producto fue registrado correctamente.';
        } else if (status === 'updated') {
          titulo = '¡Actualizado!';
          texto = 'Los cambios se guardaron con éxito.';
        } else if (status === 'deleted') {
          titulo = '¡Eliminado!';
          texto = 'El producto ha sido borrado del inventario.';
        } else if (status === 'error') {
          titulo = '¡Oops!';
          texto = 'Hubo un error al procesar la solicitud.';
          icono = 'error';
        }

        // Ejecutar la plantilla de éxito de SweetAlert
        Swal.fire({
          title: titulo,
          text: texto,
          icon: icono,
          confirmButtonColor: '#3b9b4a', // Verde de tu paleta
          draggable: true
        });

        // Limpiar la URL para que no vuelva a salir la alerta si recargan la página
        window.history.replaceState(null, null, window.location.pathname);
      }
    });

    // --- LÓGICA PARA ELIMINAR (CONFIRMACIÓN SWEETALERT) ---
    function eliminarProducto(id) {
      // Plantilla de confirmación que me pasaste, adaptada al español y a tus colores
      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Rojo para eliminar
        cancelButtonColor: '#64748b', // Gris oscuro de tu paleta
        confirmButtonText: 'Sí, ¡eliminar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          // Si dicen que sí, disparamos la eliminación en PHP
          window.location.href = "php/eliminar_producto.php?id=" + id;
        }
      });
    }

    // --- LÓGICA PARA EDITAR (Se mantiene igual) ---
    const editModal = document.getElementById('editProductModal');
    const closeEditBtn = document.getElementById('closeEditModalBtn');

    closeEditBtn.addEventListener('click', () => editModal.classList.remove('active'));

    function abrirModalEditar(producto) {
      document.getElementById('edit_id').value = producto.id;
      document.getElementById('edit_nombre').value = producto.nombre;
      document.getElementById('edit_codigo').value = producto.codigo;
      document.getElementById('edit_categoria').value = producto.categoria;
      document.getElementById('edit_stock').value = producto.stock;
      document.getElementById('edit_presentacion').value = producto.presentacion;
      document.getElementById('edit_laboratorio').value = producto.laboratorio;
      document.getElementById('edit_fecha_vencimiento').value = producto.fecha_vencimiento;
      document.getElementById('edit_fecha_llegada').value = producto.fecha_llegada;
      document.getElementById('edit_precio_compra').value = producto.precio_compra;
      document.getElementById('edit_precio_venta').value = producto.precio_venta;
      
      editModal.classList.add('active');
    }
  </script>
</body>
</html>