
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
    <title>Residentes - Portal Presidente - La Quinta</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../Img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/residentes.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  
</head>
<body>
      <header class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="logo-section">
                        <div class="logo-image">
                            <img src="../Img/logo.png" alt="Logo La Quinta">
                        </div>
                        <div class="logo-text">
                            <h1>LA QUINTA</h1>
                            <span>Portal Presidente junta de condominio</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="user-info">
                        <h5><?php  echo $_SESSION["usuario"];  ?> <br>Presidente de la junta de condominio <br> Edificio <?php echo  $_SESSION['Terraza'].$_SESSION['Edificio'];  ?></h5>
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
    <nav class="main-nav">
        <div class="container">
            <ul class="nav nav-pills flex-column flex-md-row">
                <li class="nav-item">
                    <a class="nav-link" href="PC_inicio.php">
                        <span class="nav-text">Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-text">Anuncios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="Residentes.php">
                        <span class="nav-text">Residentes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-text">Pagos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-text">Comunicación</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-text">Servicios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="PC_configuracion.php">
                        <span class="nav-text">Configuración</span>
                    </a>
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
                        <h2>Gestión de Residentes</h2>
                        <p>Administra la información de todos los residentes del Edificio A1</p>
                    </div>
                    <div class="col-md-4 text-center d-none d-md-block">
                        <div style="font-size: 60px; opacity: 0.3; color: white;">👥</div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" placeholder="Buscar por nombre, usuario, correo o teléfono..." id="searchInput">
                            <button class="btn btn-primary-custom" type="button" id="searchBtn">
                                🔍 Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Residents Table -->
            <div class="residents-section">
                <div class="section-header">
                    <h4>Lista de Residentes</h4>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table residents-table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo Electrónico</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                            <tbody id="residentsTableBody">
                                <!-- Aquí se insertarán los datos desde la base de datos -->
                                <?php include '../../Controlador/funciones/mostrarResidentes.php'; ?>
                            </tbody>
                        </table>
                        <div id="noResultsMessage" class="text-center text-danger fw-bold py-3" style="display: none;">
                                No se ha encontrado lo ingresado.
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    

<script>
    function filterTable(searchTerm) {
        const tableRows = document.querySelectorAll('#residentsTableBody tr');
        let found = false;

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        return found;
    }

    // Buscar mientras escribe (muestra mensaje inferior si no hay coincidencias)
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const found = filterTable(searchTerm);
        const message = document.getElementById('noResultsMessage');

        if (searchTerm.trim() === '') {
            message.style.display = 'none';
        } else {
            message.style.display = found ? 'none' : 'block';
        }
    });

    // Buscar con botón (solo SweetAlert si no encuentra nada y resetea)
    document.getElementById('searchBtn').addEventListener('click', function () {
        const input = document.getElementById('searchInput');
        const searchTerm = input.value.toLowerCase();
        const found = filterTable(searchTerm);

        if (!found) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin coincidencias',
                text: 'No se ha encontrado lo ingresado.',
                confirmButtonText: 'Cerrar'
            }).then(() => {
                // Limpiar campo y mostrar todos los registros
                input.value = '';
                document.querySelectorAll('#residentsTableBody tr').forEach(row => {
                    row.style.display = '';
                });
            });
        }
    });
</script>


</body>
</html>