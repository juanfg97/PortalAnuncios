<?php
session_start();
if (empty($_SESSION['usuario'])) {
    session_destroy();
    header('Location: /PortalDeAnuncios/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Comunicación - Portal Presidente Central</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet" />
     <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/P_inicio.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/P_comunicacion.css">


    <link rel="shortcut icon" href="../Img/favicon.png" type="image/x-icon" />
    
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
    <main class="main-content mb-5">
        <div class="container">
            <!-- Header Section -->
            <div class="comunicacion-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>Centro de Comunicación</h2>
                        <p>Envía comunicados a los presidentes de edificios y revisa los informes recibidos de toda la urbanización.</p>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <div style="font-size: 60px; opacity: 0.3;">💬</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Enviar Comunicados -->
                <div class="col-lg-5 mb-4">
                    <div class="comunicacion-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="card-indicator indicator-anuncios me-2">📤</div>
                            <h4 class="mb-0">Enviar Comunicado</h4>
                        </div>
                        
                        <form id="formComunicado" class="comunicado-form">
                            <div class="mb-3">
                                <label for="destinatario" class="form-label fw-bold">Destinatario</label>
                                <?php  include '../../Controlador/funciones/selecciondestinatario.php';  ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label fw-bold">Asunto</label>
                                <input type="text" class="form-control" id="asunto" placeholder="Tema del comunicado..." required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mensaje" class="form-label fw-bold">Mensaje</label>
                                <textarea class="form-control" id="mensaje" rows="6" placeholder="Escriba aquí el contenido del comunicado..." required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prioridad" class="form-label fw-bold">Prioridad</label>
                                <select class="form-select" id="prioridad">
                                    <option value="normal">📄 Normal</option>
                                    <option value="importante">⚠️ Importante</option>
                                    <option value="urgente">🚨 Urgente</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-enviar-comunicado w-100">
                                📤 Enviar Comunicado
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Informes Recibidos -->
                <div class="col-lg-7 mb-4">
                    <div class="comunicacion-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="card-indicator indicator-reportes me-2">📋</div>
                                <h4 class="mb-0">Informes de Presidentes</h4>
                            </div>
                            <span class="contador-informes">
                                <?php 
                                include '../../Controlador/conexion_bd_login.php';
                                include '../../Controlador/funciones/contarnuevosinformes.php'; echo $nuevosInformes.' informes nuevos'; ?>
                            </span>
                        </div>
                        
                        <!-- Filtros -->
                        <div class="filtros-comunicacion mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <select class="form-select form-select-sm" id="filtroTerraza">
                                        <option value="">Todas las terrazas</option>
                                        <option value="1">Terraza 1</option>
                                        <option value="2">Terraza 2</option>
                                        <option value="3">Terraza 3</option>
                                        <option value="4">Terraza 4</option>
                                        <option value="5">Terraza 5</option>
                                        <option value="6">Terraza 6</option>
                                        <option value="7">Terraza 7</option>
                                        <option value="8">Terraza 8</option>
                                        <option value="9">Terraza 9</option>
                                        <option value="10">Terraza 10</option>
                                        <option value="11">Terraza 11</option>
                                        <option value="12">Terraza 12</option>
                                        <option value="13">Terraza 13</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                <input type="text" id="filtroEdificio" class="form-control form-control-sm" placeholder="Filtrar por edificio (ej. 1A)">
                                </div>
                                <div class="col-md-6 mt-2">
    <select class="form-select form-select-sm" id="filtroPrioridad">
        <option value="">Todas las prioridades</option>
        <option value="baja">🟢 Baja - Informativo</option>
        <option value="media">🟡 Media - Requiere Atención</option>
        <option value="alta">🔴 Alta - Urgente</option>
    </select>
</div>

 <div class="col-md-6 mt-2">
        <select class="form-select form-select-sm" id="filtroTipo"  required>
            <option value="">Seleccionar tipo...</option>
            <option value="mantenimiento">🔧 Mantenimiento</option>
            <option value="seguridad">🛡️ Seguridad</option>
            <option value="financiero">💰 Financiero</option>
            <option value="limpieza">🧹 Limpieza</option>
            <option value="servicios">⚡ Servicios Públicos</option>
            <option value="reparaciones">🔨 Reparaciones</option>
            <option value="general">📋 General</option>
        </select>
    </div>

    </div>
</div>
                        
                        <!-- Lista de Informes -->
                        <div id="listaInformes" style="max-height: 600px; overflow-y: auto;">
                            <?php include '../../Controlador/funciones/mostrarinformes.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
   <footer class="main-footer">
    <div class="container d-flex justify-content-between">
        <div class="footer-logo fw-bold">LA QUINTA - Parque Residencial</div>
        <div class="footer-contact">
            <span class="me-3"><i class="bi bi-geo-alt"></i> Av. Víctor Baptista, Los Teques</span>
            <span><i class="bi bi-telephone"></i> Tel: (032) 31.1221</span>
        </div>
    </div>
</footer>

    <!-- Modal para Confirmar Envío -->
    <div class="modal fade" id="modalConfirmarEnvio" tabindex="-1" aria-labelledby="modalConfirmarEnvioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalConfirmarEnvioLabel">Confirmar Envío de Comunicado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="font-size: 40px;">📤</div>
                        <div>
                            <h6 class="mb-1">¿Está seguro de enviar este comunicado?</h6>
                            <small class="text-muted">Esta acción no se puede deshacer</small>
                        </div>
                    </div>
                    
                    <div class="border rounded p-3 bg-light">
                        <div class="row mb-2">
                            <div class="col-4"><strong>Destinatario:</strong></div>
                            <div class="col-8" id="confirmDestinatario"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Asunto:</strong></div>
                            <div class="col-8" id="confirmAsunto"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Prioridad:</strong></div>
                            <div class="col-8" id="confirmPrioridad"></div>
                        </div>
                        <div class="row">
                            <div class="col-4"><strong>Mensaje:</strong></div>
                            <div class="col-8" id="confirmMensaje"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarEnvio">
                        📤 Enviar Comunicado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Ver Informe Completo -->
    <div class="modal fade" id="modalInformeCompleto" tabindex="-1" aria-labelledby="modalInformeCompletoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalInformeCompletoLabel">Informe Completo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoInformeCompleto">
              
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../../Modelo/js/P_comunicacion.js"></script>
</body>
</html>