<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediStock - Registro de Personal</title>
  <link rel="stylesheet" href="css/globals.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="login-bg">
    <main class="login-card" style="max-width: 800px;">
      
      <div class="logo-container">
        <img src="img/logo_azul.png" alt="Logo MediStock" />
      </div>

      <h1 class="login-title">REGISTRO DE PERSONAL</h1>

      <form action="php/procesar_registro.php" method="POST">
        
        <div class="form-row">
          <div class="form-col input-group small">
            <input class="input-field" type="text" name="nombre" placeholder="Nombres" required />
          </div>
          <div class="form-col input-group small">
            <input class="input-field" type="text" name="apellido" placeholder="Apellidos" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-col input-group small cedula-group">
            <select name="tipo_doc" class="cedula-select" required>
              <option value="V-">V-</option>
              <option value="J-">J-</option>
              <option value="E-">E-</option>
            </select>
            <input class="input-field" type="text" name="cedula" placeholder="Cédula / RIF" required />
          </div>
          <div class="form-col input-group small">
            <input class="input-field" type="text" name="telefono" placeholder="Teléfono" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-col input-group small">
            <input class="input-field" type="email" name="email" placeholder="Correo Electrónico" required />
          </div>
          <div class="form-col input-group small">
            <select name="rol" class="input-field" style="color: #0000007a; cursor: pointer;" required>
              <option value="" disabled selected>Selecciona un Cargo</option>
              <option value="Vendedor">Vendedor</option>
              <option value="Administrador">Administrador</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col input-group small">
            <input class="input-field" type="password" name="password" placeholder="Contraseña" required />
          </div>
          <div class="form-col input-group small">
            <input class="input-field" type="password" name="confirm_password" placeholder="Confirmar Contraseña" required />
          </div>
        </div>

        <div class="links">
          <a href="login.php">¿Ya tienes cuenta? Inicia Sesión</a>
        </div>

        <div class="submit-btn-container">
          <button type="submit" class="submit-btn">Registrar</button>
        </div>

      </form>
    </main>
  </div>
  <script src="../../fonds/sweetalert.cjs"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const urlParams = new URLSearchParams(window.location.search);
      const status = urlParams.get('status');

      if (status) {
        let config = { icon: 'error', confirmButtonColor: '#d33' };

        if (status === 'error_empty') {
          config.title = 'Campos incompletos';
          config.text = 'Todos los campos marcados son obligatorios.';
        } else if (status === 'error_password_match') {
          config.title = 'Contraseñas no coinciden';
          config.text = 'Asegúrate de escribir la misma contraseña en ambos campos.';
        } else if (status === 'error_email_exists') {
          config.title = 'Correo ya registrado';
          config.text = 'Este correo electrónico ya pertenece a otro usuario de MediStock.';
        } else if (status === 'error_cedula_exists') {
          config.title = 'Cédula ya registrada';
          config.text = 'Este número de documento ya se encuentra en nuestra base de datos.';
        } else if (status === 'error_db') {
          config.title = 'Error de conexión';
          config.text = 'No se pudo completar el registro. Inténtalo más tarde.';
        }

        if (config.title) {
          Swal.fire(config);
        }

        window.history.replaceState(null, null, window.location.pathname);
      }
    });
  </script>
</body>
</html>