<?php
session_start();
if (empty($_SESSION['usuario'])) {
    // Redirigir si no hay sesión activa
    session_destroy();
    header('Location: /PortalDeAnuncios/index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - La Quinta</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/P_servicios.css">
    <link rel="shortcut icon" href="../Img/favicon.png" type="image/x-icon">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<body>
    <!-- Header -->
    <header class="main-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 d-flex align-items-center">
                    <div class="logo-section d-flex align-items-center">
                        <div class="logo-image me-3">
                            <img src="../Img/logo.png" alt="Logo La Quinta" height="50" />
                        </div>
                        <div class="logo-text">
                            <h1 class="h4 mb-0">LA QUINTA</h1>
                            <small>Portal Presidente Central</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="user-info">
                        <h5 class="mb-0"><?php echo $_SESSION["nombre_completo"]; ?></h5>
                        <small>Presidente Central - Urbanización</small>
                        <div class="mt-2">
                            <a href="../../Controlador/funciones/logout.php" class="btn btn-outline-primary btn-sm">
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
   <nav class="main-nav mb-4">
        <div class="container">
            <ul class="nav nav-pills flex-column flex-md-row">
                <li class="nav-item">
                    <a class="nav-link" href="P_inicio.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="P_anuncios.php">Anuncios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Presidentes.php">Presidentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="P_comunicacion.php">Comunicación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="P_servicios.php">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="P_configuracion.php">Configuración</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>Servicios </h2>
                        <p>Mantente informado sobre todos los servicios de terceros disponibles en la urbanizacion</p>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <div style="font-size: 60px; opacity: 0.3; color: white;">📋</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content Area -->
                <div class="col-lg-8">
                    <!-- Add Announcement Button -->
                    <button class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                        ➕ Agregar Nuevo Servicio
                    </button>


                    <div class="mb-3">
                        <label for="categoriaFiltro" class="form-label"><strong>Filtrar por categoría:</strong></label>
                        <select id="categoriaFiltro" class="form-control">
                        <option value="Todos">Todos</option>
                        <option value="Limpieza">Limpieza</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Emergencia">Emergencia</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Recreativo">Recreativo</option>
                        <option value="Otros">Otros</option>
                        </select>
</div>


                    <!-- Announcements List -->
                    <div id="announcementsList">
                        <!-- Anuncio 1 -->
                  
                    <?php  include '../../Controlador/funciones/mostrarservicios.php';  ?>
                   
                 

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Stats -->
                    <div class="stats-card">
                        <div class="stat-number" id="totalAnnouncements"><?php    include '../../Controlador/funciones/contarservicios.php';
                        include '../../Controlador/conexion_bd_login.php';
                        $servicios = contarServicios($conexion);
                        echo $servicios;
                        
                        ?></div>
                        <div class="stat-label">Total de Servicios</div>
                    </div>
            </div>
        </div>
    </main>

    <!-- Modal para Agregar Anuncio -->
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAnnouncementModalLabel">
                        📝 Crear Nuevo Servicio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ServicioForm" method="post">
                         <input type="hidden" id="formMode" value="crear"> <!-- 'crear' o 'editar' -->
                        <input type="hidden" id="announcementId" value="">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="nombre_servicio">
                                    <strong>Nombre del servicio *</strong>
                                </label>
                                <input type="text" class="form-control" id="nombre_servicio" 
                                       placeholder="Ingrese el nombre del servicio" required>
                            <small id="nombreError" style="color:red; display:none;">El nombre del servicio debe tener al menos 5 caracteres.</small><br>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label" for="descripcion">
                                    <strong>Contenido del Anuncio *</strong>
                                </label>
                                <textarea class="form-control" id="descripcion" rows="5" 
                                          placeholder="Describa su servicio" required></textarea>
                            <small id="descripcionError" style="color:red; display:none;">La descripción debe tener al menos 20 caracteres.</small><br>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="proveedor">
                                    <strong>Proveedor*</strong>
                                </label>
                                <input type="text" class="form-control" id="proveedor" 
                                       placeholder="Ingrese nombre del proveedor" required>
                                <small id="proveedorError" style="color:red; display:none;">El proveedor debe tener al menos 3 letras.</small>

                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="contacto">
                                    <strong>Contacto*</strong>
                                </label>
                                <input type="text" class="form-control" id="contacto" 
                                       placeholder="Ingrese informacion de contacto" required>
                                <small id="contactoError" style="color:red; display:none;">El contacto debe tener al menos 3 letras o numeros.</small>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="servicioCategory">
                                    <strong>Categoría</strong>
                                </label>
                                <select class="form-control" id="servicioCategory">
                                    <option value="Limpieza">Limpieza</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                    <option value="Emergencia">Emergencia</option>
                                    <option value="Eventos">Eventos</option>
                                    <option value="Recreativo">Recreativo</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label" for="servicioImage">
                                    <strong>Imagen (opcional)</strong>
                                </label>
                                <input type="file" class="form-control" id="servicioImage" 
                                       accept="image/*" onchange="previewImage(this)">
                                <small class="text-muted">Formatos permitidos: JPG, PNG, GIF (máximo 5MB)</small>
                                <img id="imagePreview" class="image-preview" alt="Vista previa">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        ❌ Cancelar
                    </button>
                    <button type="submit"  form="ServicioForm" id="submitAnnouncementBtn" class="btn btn-primary">
                        📤 Publicar Anuncio
                    </button>
                </div>
            </div>
        </div>
    </div>
          <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    LA QUINTA - Parque Residencial
                </div>
                <div class="footer-contact">
                    <span><i class="bi bi-geo-alt"></i> Av. Víctor Baptista, Los Teques</span>
                    <span><i class="bi bi-telephone"></i> Tel: (032) 31.1221</span>
                </div>
            </div>
        </div>
    </footer>
    <script src="../../Modelo/js/servicio.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
   
  

</body>
</html>