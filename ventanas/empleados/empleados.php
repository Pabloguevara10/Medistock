<?php
session_start();
require '../login/php/conexion.php';

// Seguridad Estricta: Solo Administradores
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Administrador') { 
    header("Location: ../login/login.php?status=error_acceso"); 
    exit(); 
}

$query_emp = "SELECT id, cedula, nombre, apellido, email, telefono, rol FROM usuarios ORDER BY rol ASC, nombre ASC";
$resultado = $conn->query($query_emp);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MediStock - Empleados</title>
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
            <div class="user-avatar">A</div>
            <span><?php echo $_SESSION['nombre_completo']; ?></span>
          </div>
        </header>

        <aside class="sidebar">
          <div class="sidebar-title">MENÚ PRINCIPAL</div>
          <a href="../menuprincipal/menu.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/home.png"> <span>Inicio</span></a>
          <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/inventory.png"> <span>Inventario</span></a>
          <a href="../reportes/reportes.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/report.png"> <span>Reportes</span></a>
          <a href="../proovedores/proovedores.php" class="sidebar-item" style="color: inherit; text-decoration: none;"><img src="../../img/cargamento.png"> <span>Proveedores</span></a>
          <div class="sidebar-item active"><img src="../../img/empleados.png"> <span>Empleados</span></div>
          <a href="../login/login.php" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;"><img src="../../img/salir.png"> <span>Salir</span></a>
        </aside>

        <main class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 class="page-title" style="margin: 0;">Gestión de Empleados</h1>
                <a href="../login/registro.php" class="btn-submit-pill" style="text-decoration:none; display:inline-block; width:auto;">+ Nuevo Usuario</a>
            </div>
            
            <div class="table-section">
                <div class="table-responsive">
                    <table class="inventory-table">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre Completo</th>
                                <th>Rol en Sistema</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($emp = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($emp['cedula']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']); ?></strong></td>
                                    <td>
                                        <span class="badge <?php echo $emp['rol'] == 'Administrador' ? 'optimo' : 'critico'; ?>" style="background-color: <?php echo $emp['rol'] == 'Administrador' ? '#3b7d85' : '#64748b'; ?>">
                                            <?php echo htmlspecialchars($emp['rol']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                    <td><?php echo htmlspecialchars($emp['telefono']); ?></td>
                                    
                                    <?php $datos_json = htmlspecialchars(json_encode($emp), ENT_QUOTES, 'UTF-8'); ?>
                                    <td class="action-icons">
                                        <img src="../../img/edit.png" alt="Editar" title="Editar" style="cursor:pointer;" onclick="abrirModal(<?php echo $datos_json; ?>)" />
                                        <?php if($_SESSION['user_id'] != $emp['id']): // Evita que se borre a sí mismo ?>
                                            <img src="../../img/borrar.png" alt="Eliminar" title="Eliminar" style="cursor:pointer;" onclick="eliminarEmp(<?php echo $emp['id']; ?>)" />
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="editModal">
      <div class="modal-content new-style-modal">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <div class="modal-header-centered"><h2>EDITAR USUARIO</h2></div>
        <form class="add-product-form-new" action="php/actualizar_empleado.php" method="POST">
          <input type="hidden" name="id" id="e_id" />
          <div class="row-2-cols">
            <input type="text" name="nombre" id="e_nombre" class="input-pill" required />
            <input type="text" name="apellido" id="e_apellido" class="input-pill" required />
          </div>
          <input type="text" name="telefono" id="e_telefono" class="input-pill full-width" required />
          <select name="rol" id="e_rol" class="input-pill full-width" required>
              <option value="Administrador">Administrador</option>
              <option value="Vendedor">Vendedor</option>
          </select>
          <div class="submit-container">
            <button type="submit" class="btn-submit-pill" style="background-color: #f59e0b;">Actualizar</button>
          </div>
        </form>
      </div>
    </div>

    <script src="../../fonds/sweetalert.cjs"></script>
    <script>
      const modal = document.getElementById('editModal');
      document.getElementById('closeModalBtn').addEventListener('click', () => modal.classList.remove('active'));

      function abrirModal(emp) {
        document.getElementById('e_id').value = emp.id;
        document.getElementById('e_nombre').value = emp.nombre;
        document.getElementById('e_apellido').value = emp.apellido;
        document.getElementById('e_telefono').value = emp.telefono;
        document.getElementById('e_rol').value = emp.rol;
        modal.classList.add('active');
      }

      function eliminarEmp(id) {
        Swal.fire({
          title: '¿Eliminar empleado?', text: "Esta acción no se puede deshacer.", icon: 'warning',
          showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Sí, eliminar'
        }).then((res) => { if (res.isConfirmed) window.location.href = "php/eliminar_empleado.php?id=" + id; });
      }

      // Alertas URL
      const params = new URLSearchParams(window.location.search);
      const status = params.get('status');
      if(status === 'updated') Swal.fire('Actualizado', 'Datos guardados', 'success');
      else if(status === 'deleted') Swal.fire('Eliminado', 'Usuario borrado', 'success');
      else if(status === 'error') Swal.fire('Error', 'Ocurrió un problema', 'error');
      window.history.replaceState(null, null, window.location.pathname);
    </script>
</body>
</html>