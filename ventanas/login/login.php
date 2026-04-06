<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediStock - Iniciar Sesión</title>
  <link rel="stylesheet" href= "css/globals.css" />
  <link rel="stylesheet" href= "css/style.css" />
</head>
<body>
  <div class="login-bg">
    <main class="login-card">
      
      <div class="logo-container">
        <img src= "img/logo_azul.png" alt="Logo MediStock" />
      </div>

      <h1 class="login-title">INICIAR SESIÓN</h1>

      <form action="php/validar_login.php" method="POST">
        
        <div class="input-group">
          <div class="icon-circle">
            <img src= "img/user.png" alt="Usuario" />
          </div>
          <input class="input-field" type="email" name="email" placeholder="Correo Electrónico" required />
        </div>

        <div class="input-group">
          <div class="icon-circle">
            <img src= "img/key.png" alt="Contraseña" />
          </div>
          <input class="input-field" type="password" name="password" placeholder="******************" required />
        </div>

        <div class="links">
          <a href="registro.php">¿No tienes cuenta? Regístrate aquí</a>
        </div>

        <div class="submit-btn-container">
          <button type="submit" class="submit-btn">Entrar</button>
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
        let titulo = '';
        let texto = '';
        let icono = 'error'; // Por defecto será error
        let colorConfirm = '#d33';

        if (status === 'error_empty') {
          titulo = 'Campos Vacíos';
          texto = 'Por favor, ingresa tu correo y contraseña.';
        } else if (status === 'error_password') {
          titulo = 'Acceso Denegado';
          texto = 'La contraseña ingresada es incorrecta.';
        } else if (status === 'error_notfound') {
          titulo = 'Usuario no encontrado';
          texto = 'No existe ninguna cuenta registrada con ese correo.';
        } else if (status === 'success_registro') {
          titulo = '¡Registro Exitoso!';
          texto = 'Tu cuenta ha sido creada. Ya puedes iniciar sesión.';
          icono = 'success';
          colorConfirm = '#3b9b4a'; // Verde corporativo
        }

        Swal.fire({
          title: titulo,
          text: texto,
          icon: icono,
          confirmButtonColor: colorConfirm
        });

        // Limpiamos la URL para que no vuelva a salir la alerta si recarga la página
        window.history.replaceState(null, null, window.location.pathname);
      }
    });
  </script>
</body>
</html>