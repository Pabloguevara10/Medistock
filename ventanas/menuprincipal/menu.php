<?php
session_start();
// Agregamos ../ para salir de menuprincipal/ y entrar a login/
require '../login/php/conexion.php'; 

// 1. Obtener métricas rápidas para las tarjetas superiores
$res_prod = $conn->query("SELECT COUNT(*) as total FROM productos");
$total_productos = $res_prod ? $res_prod->fetch_assoc()['total'] : 0;

$res_prov = $conn->query("SELECT COUNT(*) as total FROM proveedores");
$total_proveedores = $res_prov ? $res_prov->fetch_assoc()['total'] : 0;

$res_criticos = $conn->query("SELECT COUNT(*) as total FROM productos WHERE stock <= 10 OR estado = 'Vencido'");
$total_criticos = $res_criticos ? $res_criticos->fetch_assoc()['total'] : 0;

// 2. Obtener las últimas alertas para el panel lateral
$alertas = $conn->query("SELECT nombre, stock, estado FROM productos WHERE stock <= 10 OR estado = 'Vencido' ORDER BY stock ASC LIMIT 4");

// 3. Obtener la distribución de productos por categoría para el gráfico
$query_categorias = "SELECT categoria, COUNT(*) as cantidad FROM productos GROUP BY categoria";
$resultado_categorias = $conn->query($query_categorias);

$categorias_nombres = [];
$categorias_cantidades = [];

if($resultado_categorias) {
    while($row = $resultado_categorias->fetch_assoc()) {
        $categorias_nombres[] = $row['categoria'];
        $categorias_cantidades[] = $row['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <title>MediStock - Panel Principal</title>
  
  <link rel="stylesheet" href="../../css/globals.css" /> 
  <link rel="stylesheet" href="../../css/style.css" />
  
  <script src="../../fonds/charts.cjs"></script>

  <style>
    .dashboard-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
      margin-top: 20px;
    }
    
    .welcome-banner {
      background: linear-gradient(135deg, #3b7d85 0%, #2c5e64 100%);
      border-radius: 12px;
      padding: 30px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 15px rgba(59, 125, 133, 0.2);
    }
    .welcome-text h2 { margin: 0; font-size: 24px; font-weight: bold; }
    .welcome-text p { margin: 8px 0 0 0; font-size: 15px; opacity: 0.9; }
    
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin-top: 20px;
      margin-bottom: 25px;
    }
    .action-card {
      background: white;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      text-decoration: none;
      color: #1e293b;
      font-weight: bold;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      font-size: 14px;
    }
    .action-card:hover {
      border-color: #3b7d85;
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .action-card img { width: 32px; margin-bottom: 10px; }

    .dashboard-box {
      background: white;
      border-radius: 12px;
      padding: 20px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .box-header {
      font-size: 16px;
      font-weight: bold;
      color: #334155;
      border-bottom: 2px solid #f1f5f9;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    .chart-container {
      position: relative;
      height: 250px; 
      width: 100%;
    }

    @media (max-width: 900px) {
      .dashboard-grid { grid-template-columns: 1fr; }
      .quick-actions { grid-template-columns: 1fr 1fr; }
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
      <div class="header-user">
        <div class="user-avatar">Ad</div>
        <span>Administrador</span>
      </div>
    </header>

    <aside class="sidebar">
      <div class="sidebar-title">MENÚ PRINCIPAL</div>
      <div class="sidebar-item active">
        <img src="../../img/home.png" alt="" /> <span>Inicio</span>
      </div>
      <a href="../inventario/inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/inventory.png" alt="" /> <span>Inventario</span>
      </a>
      <div class="sidebar-item">
        <img src="../../img/report.png" alt="" /> <span>Reportes</span>
      </div>
      <a href="../proovedores/proovedores.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../../img/cargamento.png" alt="" /> <span>Proveedores</span>
      </a>
      <div class="sidebar-item">
        <img src="../../img/empleados.png" alt="" /> <span>Empleados</span>
      </div>
      <a href="../login/login.html" class="sidebar-item logout-btn" style="color: inherit; text-decoration: none;">
        <img src="../../img/salir.png" alt="" /> <span>Salir</span>
      </a>
    </aside>

    <main class="main-content">
      <h1 class="page-title">Panel de Control</h1>

      <div class="top-widgets">
        <div class="summary-cards" style="width: 100%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
          <div class="card blue">
            <div class="card-title">Total Productos</div>
            <div class="card-value"><?php echo $total_productos; ?></div>
          </div>
          <div class="card green">
            <div class="card-title">Proveedores Activos</div>
            <div class="card-value"><?php echo $total_proveedores; ?></div>
          </div>
          <div class="card red">
            <div class="card-title" style="color:white;">Alertas Activas</div>
            <div class="card-value" style="color:white;"><?php echo $total_criticos; ?></div>
          </div>
        </div>
      </div>

      <div class="dashboard-grid">
        
        <div class="dashboard-main-area">
          <div class="welcome-banner">
            <div class="welcome-text">
              <h2>¡Hola de nuevo, <?php echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'Administrador'; ?>! 👋</h2>
              <p>Aquí tienes un resumen del estado actual de tu farmacia. Todo está funcionando correctamente.</p>
            </div>
            <div>
              <img src="../../img/usuario.png" alt="User" style="width: 60px; opacity: 0.8;">
            </div>
          </div>

          <div class="quick-actions">
            <a href="../inventario/inventario.php" class="action-card">
              <img src="../../img/pastilla.png" alt=""><br>Gestionar Inventario
            </a>
            <a href="../proovedores/proovedores.php" class="action-card">
              <img src="../../img/camion.png" alt=""><br>Ver Proveedores
            </a>
            <a href="#" class="action-card">
              <img src="../../img/report.png" alt=""><br>Generar Reportes
            </a>
          </div>

          <div class="dashboard-box">
            <div class="box-header">📊 Distribución de .. por Categoría</div>
            <div class="chart-container">
              <?php if (empty($categorias_nombres)): ?>
                  <div style="display:flex; height:100%; align-items:center; justify-content:center; color:#64748b;">
                      Aún no hay productos registrados en el ...
                  </div>
              <?php else: ?>
                  <canvas id="categoriasChart"></canvas>
              <?php endif; ?>
            </div>
          </div>

        </div>

        <div class="dashboard-box" style="height: fit-content;">
          <div class="box-header">🔔 Avisos del Sistema</div>
          
          <?php
          if ($alertas && $alertas->num_rows > 0) {
              while($alerta = $alertas->fetch_assoc()) {
                  if ($alerta['estado'] == 'Vencido') {
                      echo "<div style='background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 10px; margin-bottom: 10px; border-radius: 4px; font-size: 13px;'>";
                      echo "🚨 <b>Vencido:</b> " . htmlspecialchars($alerta['nombre']);
                      echo "</div>";
                  } else {
                      echo "<div style='background-color: #fff7ed; border-left: 4px solid #f97316; color: #9a3412; padding: 10px; margin-bottom: 10px; border-radius: 4px; font-size: 13px;'>";
                      echo "⚠️ <b>Stock Crítico:</b> " . htmlspecialchars($alerta['nombre']) . " (" . $alerta['stock'] . " uds)";
                      echo "</div>";
                  }
              }
              if ($total_criticos > 4) {
                  echo "<div style='text-align:center; margin-top:15px;'><a href='../../...php' style='color:#3b7d85; font-size:13px; font-weight:bold;'>Ver todas las alertas &rarr;</a></div>";
              }
          } else {
              echo "<div style='text-align:center; padding: 30px 10px; color:#64748b; font-size: 14px;'>";
              echo "<img src='../../img/tarjet.png' style='width:40px; margin-bottom:10px; opacity:0.5;'><br>";
              echo "Todo en orden. No tienes notificaciones pendientes.";
              echo "</div>";
          }
          ?>
        </div>

      </div>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const nombres = <?php echo json_encode($categorias_nombres); ?>;
        const cantidades = <?php echo json_encode($categorias_cantidades); ?>;
        const ctxElement = document.getElementById('categoriasChart');
        
        if (ctxElement) {
            const ctx = ctxElement.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut', 
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Productos Registrados',
                        data: cantidades,
                        backgroundColor: ['#3b7d85', '#3b9b4a', '#f59e0b', '#64748b', '#0ea5e9', '#1e293b'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { padding: 20, font: { family: "'Roboto', sans-serif", size: 13 } }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
  </script>
</body>
</html>