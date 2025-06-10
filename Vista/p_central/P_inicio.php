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
    <title>Portal Presidente Central - La Quinta</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../css/P_inicio.css" />
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
            <!-- Welcome Section -->
            <div class="welcome-section mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>Bienvenido, <?php echo $_SESSION["nombre_completo"]; ?></h2>
                        <p>Panel de control central para la gestión de toda la Urbanización La Quinta. Coordina con los presidentes de cada edificio, publica anuncios generales y supervisa los servicios de toda la comunidad.</p>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <div style="font-size: 80px; opacity: 0.3; color: white;">🏘️</div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="dashboard-card p-3 border rounded h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="card-indicator indicator-presidentes me-2">P</div>
                            <span class="card-title h6 mb-0">Presidentes Activos</span>
                        </div>
                        <div class="stat-number text-success fs-4">
                            <?php
                            include '../../Controlador/conexion_bd_login.php';
                            include '../../Controlador/funciones/contarpresidentes.php';
                            echo contarPresidentes($conexion);
                            ?>
                        </div>
                        <div class="stat-label">En toda la urbanización</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="dashboard-card p-3 border rounded h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="card-indicator indicator-anuncios me-2">A</div>
                            <span class="card-title h6 mb-0">Anuncios Generales</span>
                        </div>
                        <div class="stat-number text-info fs-4">
                            <?php
                            include '../../Controlador/funciones/contaranuncios.php';
                            echo contarAnuncios($conexion);
                            ?>
                        </div>
                        <div class="stat-label">Activos en toda la urbanización</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="dashboard-card p-3 border rounded h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="card-indicator indicator-comunicacion me-2">I</div>
                            <span class="card-title h6 mb-0">Informes Nuevos</span>
                        </div>
                        <div class="stat-number text-danger fs-4">
<?php include '../../Controlador/funciones/contarnuevosinformes.php'; echo $nuevosInformes;  ?>


                        </div>
                        <div class="stat-label">De presidentes de edificios</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="dashboard-card p-3 border rounded h-100">
                        <div class="d-flex align-items-center mb-2">
                            <div class="card-indicator indicator-servicios me-2">S</div>
                            <span class="card-title h6 mb-0">Servicios Activos</span>
                        </div>
                        <div class="stat-number text-success fs-4"><?php    include '../../Controlador/funciones/contarservicios.php';
                        
                        $servicios = contarServicios($conexion);
                        echo $servicios;
                        
                        ?></div>
                        <div class="stat-label">Mantenimiento, Limpieza, Emergencia</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions + Recent Announcements -->
            <div class="row">
                <!-- Quick Actions -->
                <div class="col-lg-4 mb-4">
                    <div class="dashboard-card p-4 border rounded h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="card-indicator indicator-servicios me-2">+</div>
                            <span class="card-title h5 mb-0">Acciones Rápidas</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="P_anuncios.php" class="btn btn-outline-secondary btn-sm">
                                <i class="me-2">📢</i>Añadir Anuncio
                            </a>
                            <a href="P_servicios.php" class="btn btn-outline-secondary btn-sm">
                                <i class="me-2">🔧</i>Añadir Servicio
                            </a>
                            <a href="P_comunicacion.php" class="btn btn-outline-secondary btn-sm">
                                <i class="me-2">📧</i>Comunicado General
                            </a>
                            
                        </div>
                    </div>
                </div>

                <!-- Recent Announcements -->
                <div class="col-lg-8 mb-4">
                    <div class="dashboard-card p-4 border rounded h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="card-indicator indicator-anuncios me-2">📢</div>
                                <span class="card-title h5 mb-0">Anuncios Recientes</span>
                            </div>
                            <a href="P_anuncios.php" class="btn btn-outline-primary btn-sm">Ver Todos</a>
                        </div>
                        
                        <div id="announcementsList" class="announcements-container" style="max-height: 400px; overflow-y: auto;">
                            <?php include '../../Controlador/funciones/mostraranunciosrecientes.php'; ?>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>