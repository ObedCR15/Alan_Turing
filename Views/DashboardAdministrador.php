<?php
session_start();
require_once('../Controllers/DashboardAdministradorController.php');
$dashboardController = new DashboardAdministradorController();

// Obtener los datos del dashboard
$datos = $dashboardController->obtenerDatosDashboard();
// Verificar si el administrador está autenticado
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Mostrar el mensaje de éxito o error
if (isset($_SESSION['mensaje'])):
?>
    <script>
        Swal.fire({
            icon: '<?= strpos($_SESSION['mensaje'], '✅') !== false ? 'success' : 'error' ?>',
            title: '<?= $_SESSION['mensaje'] ?>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<!-- Aquí va el contenido de tu Dashboard -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema Académico de Gestión" />
    <meta name="author" content="Neal Napa Fuentes" />
    <title>Dashboard - Alan Turing</title>
    
    <!-- Inclusión de archivos CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Estilo personalizado para las tarjetas */
        .card-stat {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .stat-icon {
            font-size: 3rem;
            opacity: 0.8;
        }
        .card-footer {
            background-color: #343a40;
            color: white;
        }
        .card-footer a {
            color: white;
            text-decoration: none;
        }
        .card-footer a:hover {
            text-decoration: underline;
        }
        .header-title {
            font-size: 2rem;
            font-weight: bold;
            color: #495057;
        }
        .breadcrumb {
            background-color: #f8f9fa;
        }
        .card-body h2 {
            font-size: 1.75rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- Incluir el archivo de header -->
    <?php include('header.php'); ?>
    
    <div id="layoutSidenav">
        <!-- Incluir el archivo de sidebar -->
        <?php include('sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="header-title mt-4">Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?></h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"> </li>
                    </ol>
                    
                    <div class="row g-4">
                        <!-- Tarjeta de Pagos -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card-stat bg-primary text-white mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">PAGOS</h5>
                                        <h2 class="card-text">S/ <?= number_format($datos['totalPagos'], 2) ?></h2>
                                    </div>
                                    <i class="fas fa-coins stat-icon"></i>
                                </div>
                                <a href="GestionarPagos.php" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center">
                                    <span>Ver Detalles</span>
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Tarjeta de Matrículas -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card-stat bg-warning text-white mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">MATRÍCULAS</h5>
                                        <h2 class="card-text"><?= number_format($datos['totalMatriculas']) ?></h2>
                                    </div>
                                    <i class="fas fa-user-graduate stat-icon"></i>
                                </div>
                                <a href="GestionarMatricula.php" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center">
                                    <span>Ver Detalles</span>
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Tarjeta de Programas -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card-stat bg-success text-white mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">PROGRAMAS</h5>
                                        <h2 class="card-text"><?= number_format($datos['totalProgramas']) ?></h2>
                                    </div>
                                    <i class="fas fa-book-open stat-icon"></i>
                                </div>
                                <a href="GestionarProgramas.php" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center">
                                    <span>Ver Detalles</span>
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sección para Gráficos -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Estadísticas de Matrículas
                                </div>
                                <div class="card-body">
                                    <canvas id="matriculasChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-pie me-1"></i>
                                    Distribución de Pagos
                                </div>
                                <div class="card-body">
                                    <canvas id="pagosChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; Sistema Académico <?= date('Y') ?></div>
                        <div class="d-none d-md-block">
                            <a href="politica-privacidad.php" class="text-decoration-none text-muted">Privacidad</a>
                            <span class="mx-2">|</span>
                            <a href="terminos.php" class="text-decoration-none text-muted">Términos</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts esenciales -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // Gráfico de Barras para Matrículas
        const ctxBar = document.getElementById('matriculasChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Matrículas Mensuales',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Gráfico de Doughnut para Pagos
        const ctxDoughnut = document.getElementById('pagosChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Pagos Completados', 'Pagos Pendientes'],
                datasets: [{
                    data: [75, 25],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
