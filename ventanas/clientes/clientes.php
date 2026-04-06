<?php
session_start();
require '../login/php/conexion.php';

// Seguridad
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login/login.php"); 
    exit(); 
}

// Consultamos los clientes
$query_clientes = "SELECT * FROM clientes ORDER BY nombre ASC";
$resultado = $conn->query($query_clientes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediStock - Directorio de Clientes</title>
    <link rel="stylesheet" href="../../css/globals.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="app-container">
        
        <header class="header">
          <div class="header-left">
            <img class="logo" src="../../img/logo.png" alt="Logo" />
            <img class="texto" src="../../img/tipografia.png" alt="MediStock" />
          </div>
          <div class="header-user">
            <div class="user-avatar"><?php echo htmlspecialchars(substr($_SESSION['rol'], 0, 1)); ?></div>
            <span><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></span>
          </div>
        </header>

        <aside class="sidebar">
          <div class="sidebar-title">MENÚ PRINCIPAL</div>
          
          <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
            <a href="../menuprincipal/menu.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
                <img src="../../img/home.png" alt=""> <span>Inicio</span>
            </a>
            <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
                <img src="../../img/inventory.png" alt=""> <span>Inventario</span>
            </a>
            <a href="../proovedores/proovedores.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
                <img src="../../img/cargamento.png" alt="" /> <span>Proveedores</span>
            </a>
          <?php endif; ?>
          
          <a href="../vendedor/vendedor.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
              <img src="../../img/pastilla.png" alt=""> <span>Punto de Venta</span>
          </a>
          
          <div class="sidebar-item active">
              <img src="../../img/usuario.png" alt=""> <span>Clientes</span>
          </div>
          
          <a href="../login/login.php" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;">
              <img src="../../img/salir.png" alt=""> <span>Salir</span>
          </a>
        </aside>

        <main class="main-content">
            <h1 class="page-title">Directorio de Clientes</h1>
            
            <div class="table-section">
                <div class="table-responsive">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Teléfono</th>
                                <th>Correo Electrónico</th>
                                <th>Dirección</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($resultado && $resultado->num_rows > 0): ?>
                                <?php while($c = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($c['cedula']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($c['apellido']); ?></td>
                                        <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                                        <td><?php echo isset($c['email']) ? htmlspecialchars($c['email']) : 'N/A'; ?></td>
                                        <td><?php echo isset($c['direccion']) && !empty($c['direccion']) ? htmlspecialchars($c['direccion']) : '<span style="color:#94a3b8; font-style:italic;">No registrada</span>'; ?></td>
                                        
                                        <?php $datos_json = htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8'); ?>
                                        <td class="action-icons">
                                            <img src="../../img/edit.png" alt="Editar" title="Editar" style="cursor:pointer;" onclick="abrirModalEditar(<?php echo $datos_json; ?>)" />
                                            <img src="../../img/borrar.png" alt="Eliminar" title="Eliminar" style="cursor:pointer;" onclick="eliminarCliente(<?php echo $c['id']; ?>)" />
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                                        No hay clientes registrados en el sistema.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="editClientModal">
      <div class="modal-content new-style-modal">
        <span class="close-btn" id="closeEditModalBtn">&times;</span>
        <div class="modal-header-centered">
          <h2>EDITAR CLIENTE</h2>
          <img src="../../img/usuario.png" alt="Usuario" class="modal-logo" style="opacity: 0.7;" /> 
        </div>

        <form class="add-product-form-new" action="php/actualizar_cliente.php" method="POST">
          <input type="hidden" name="id" id="edit_id" />
          
          <div class="row-2-cols">
            <input type="text" name="nombre" id="edit_nombre" class="input-pill" required placeholder="Nombres" />
            <input type="text" name="apellido" id="edit_apellido" class="input-pill" required placeholder="Apellidos" />
          </div>
          
          <div class="row-2-cols">
            <input type="text" name="cedula" id="edit_cedula" class="input-pill" required placeholder="Cédula" />
            <input type="text" name="telefono" id="edit_telefono" class="input-pill" required placeholder="Teléfono" />
          </div>
          
          <input type="email" name="email" id="edit_email" class="input-pill full-width" placeholder="Correo Electrónico" />
          <input type="text" name="direccion" id="edit_direccion" class="input-pill full-width" placeholder="Dirección" />

          <div class="submit-container">
            <button type="submit" class="btn-submit-pill" style="background-color: #f59e0b;">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>

    <script src="../../fonds/sweetalert.cjs"></script>
    <script>
      // --- LÓGICA DE ALERTAS URL ---
      document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status) {
          let titulo = ''; let texto = ''; let icono = 'success';
          
          if (status === 'updated') {
            titulo = '¡Actualizado!'; texto = 'Los datos del cliente se guardaron con éxito.';
          } else if (status === 'deleted') {
            titulo = '¡Eliminado!'; texto = 'El cliente ha sido borrado del sistema.';
          } else if (status === 'error_delete') {
            titulo = 'Acción Denegada'; texto = 'No se puede eliminar este cliente porque ya tiene ventas registradas en el sistema.'; icono = 'error';
          } else if (status === 'error') {
            titulo = '¡Oops!'; texto = 'Hubo un problema al procesar la solicitud.'; icono = 'error';
          }

          if (titulo) {
            Swal.fire({ title: titulo, text: texto, icon: icono, confirmButtonColor: '#3b9b4a' });
            window.history.replaceState(null, null, window.location.pathname);
          }
        }
      });

      // --- LÓGICA DEL MODAL DE EDICIÓN ---
      const editModal = document.getElementById('editClientModal');
      const closeEditBtn = document.getElementById('closeEditModalBtn');
      
      closeEditBtn.addEventListener('click', () => editModal.classList.remove('active'));

      function abrirModalEditar(cliente) {
        document.getElementById('edit_id').value = cliente.id;
        document.getElementById('edit_nombre').value = cliente.nombre;
        document.getElementById('edit_apellido').value = cliente.apellido;
        document.getElementById('edit_cedula').value = cliente.cedula;
        document.getElementById('edit_telefono').value = cliente.telefono;
        document.getElementById('edit_email').value = cliente.email || '';
        document.getElementById('edit_direccion').value = cliente.direccion || '';
        
        editModal.classList.add('active');
      }

      // --- LÓGICA PARA ELIMINAR ---
      function eliminarCliente(id) {
        Swal.fire({
          title: '¿Eliminar cliente?',
          text: "Se borrará del directorio permanentemente.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "php/eliminar_cliente.php?id=" + id;
          }
        });
      }
    </script>
</body>
</html>