<?php
session_start();
// require 'login/php/conexion.php'; // Lo descomentaremos cuando hagamos el backend
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta charset="utf-8" />
  <title>MediStock - Proveedores</title>
  <link rel="stylesheet" href="../css/globals.css" /> 
  <link rel="stylesheet" href="../css/style.css" />
  
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
      border-left: 5px solid #3b7d85; /* Color corporativo */
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
  </style>
</head>
<body>
  <div class="app-container">
    
    <header class="header">
      <div class="header-left">
        <img class="logo" src="../img/logo.png" alt="Logo" />
        <img class="texto" src="../img/tipografia.png" alt="MediStock" />
      </div>
      <div class="header-search">
        <input type="text" id="buscador-proveedor" placeholder="Buscar proveedores..." />
        <img src="../img/buscar.png" alt="Buscar" />
      </div>
      <div class="header-user">
        <div class="user-avatar">Ad</div>
        <span>Administrador</span>
      </div>
    </header>

    <aside class="sidebar">
      <div class="sidebar-title">MENÚ PRINCIPAL</div>
      <div class="sidebar-item">
        <img src="../img/home.png" alt="" /> <span>Inicio</span>
      </div>
      <a href="inventario.php" class="sidebar-item" style="color: inherit; text-decoration: none;">
        <img src="../img/inventory.png" alt="" /> <span>Inventario</span>
      </a>
      <div class="sidebar-item">
        <img src="../img/report.png" alt="" /> <span>Reportes</span>
      </div>
      <div class="sidebar-item active">
        <img src="../img/cargamento.png" alt="" /> <span>Proveedores</span>
      </div>
      <div class="sidebar-item">
        <img src="../img/empleados.png" alt="" /> <span>Empleados</span>
      </div>
      <div class="sidebar-item logout-btn">
        <img src="../img/salir.png" alt="" /> <span>Salir</span>
      </div>
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
            <img src="../img/plus.png" alt="" /> Nuevo Proveedor
          </button>
        </div>

        <div class="providers-grid">
          
          <div class="provider-card">
            <div class="provider-header">
              <div>
                <h3 class="provider-name">Laboratorios Leti</h3>
                <span class="provider-rif">J-00012345-6</span>
              </div>
              <div class="icon-actions">
                <img src="../img/edit.png" alt="Editar" title="Editar">
                <img src="../img/borrar.png" alt="Eliminar" title="Eliminar">
              </div>
            </div>
            <div>
              <span class="provider-categories">Antibióticos, Analgésicos</span>
            </div>
            <div class="provider-info">
               <img src="../img/ubi.png" alt="Ubicación" style="width:16px;"> Zona Industrial, Caracas
            </div>
            <div class="provider-info">
               <img src="../img/email.png" alt="Email" style="width:16px;"> contacto@leti.com.ve
            </div>
            <div class="provider-actions">
              <button class="btn-pedido">Generar Pedido</button>
            </div>
          </div>

          <div class="provider-card">
            <div class="provider-header">
              <div>
                <h3 class="provider-name">Bayer S.A.</h3>
                <span class="provider-rif">J-29384756-1</span>
              </div>
              <div class="icon-actions">
                <img src="../img/edit.png" alt="Editar" title="Editar">
                <img src="../img/borrar.png" alt="Eliminar" title="Eliminar">
              </div>
            </div>
            <div>
              <span class="provider-categories">Alergias, General</span>
            </div>
            <div class="provider-info">
               <img src="../img/ubi.png" alt="Ubicación" style="width:16px;"> Av. Principal de La Trinidad
            </div>
            <div class="provider-info">
               <img src="../img/email.png" alt="Email" style="width:16px;"> ventas@bayer.com
            </div>
            <div class="provider-actions">
              <button class="btn-pedido">Generar Pedido</button>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>

  <script>
    // Lógica rápida para buscar proveedores por nombre en las tarjetas
    document.addEventListener("DOMContentLoaded", function() {
      const searchInput = document.getElementById('buscador-proveedor');
      const providerCards = document.querySelectorAll('.provider-card');

      searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        providerCards.forEach(card => {
          const cardText = card.textContent.toLowerCase();
          if (cardText.includes(searchTerm)) {
            card.style.display = 'flex'; // Usamos flex porque las cards tienen display flex
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>