<?php
session_start();
date_default_timezone_set('America/Lima'); 
require_once "../Config/conexion.php";
require_once "../Controllers/DashboardEstudianteController.php";

$controller = new DashboardEstudianteController($pdo);
$resumenEstudiante = $controller->cargarResumenEstudiante();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Alan Turing</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .card-dashboard {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .card-pensiones {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }
        .card-programas {
            background: linear-gradient(135deg, #10b981, #34d399);
        }
        .card-icon {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        .card-title {
            font-size: 1.3rem;
            color: white;
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
        }
        .dashboard-btn {
            font-size: 1rem;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            transition: all 0.3s ease;
        }
        .dashboard-btn:hover {
            background: white;
            color: #1e40af;
        }
        .welcome-message {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(59,130,246,0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }
    </style>
</head>
<body class="sb-nav-fixed">

    <!-- Header -->
    <?php include 'headerEstudiante.php'; ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include 'sidebarEstudiante.php'; ?>

        <!-- Contenido Principal -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 py-4">

                    <!-- Bienvenida -->
                    <div class="welcome-message">
                        <h2 class="mb-3">¡Hola, <?= htmlspecialchars($_SESSION['student_name']) ?>! 🎓</h2>
                        <p class="mb-0">Tu progreso académico actualizado al <?= date('d/m/Y') ?></p>
                    </div>

                    <!-- Tarjetas de Resumen -->
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <!-- Pensiones -->
                        <div class="col">
                            <div class="card card-dashboard card-pensiones">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-stack card-icon"></i>
                                    <h5 class="card-title">Total de Pensiones</h5>
                                    <p class="card-text"><?= $resumenEstudiante['totalPensiones'] ?></p>
                                    <a href="EstudiantePago.php" class="dashboard-btn">
                                        <i class="bi bi-arrow-right me-2"></i>Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Programas -->
                        <div class="col">
                            <div class="card card-dashboard card-programas">
                                <div class="card-body text-center">
                                    <i class="bi bi-clipboard-data card-icon"></i>
                                    <h5 class="card-title">Total de Programas</h5>
                                    <p class="card-text"><?= $resumenEstudiante['totalProgramas'] ?></p>
                                    <a href="EstudianteCalificacion.php" class="dashboard-btn">
                                        <i class="bi bi-arrow-right me-2"></i>Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Principal -->
                    <div class="text-center mt-4">
                        <a href="EstudiantePensiones.php" class="btn btn-primary">
                            <i class="bi bi-graph-up me-2"></i>Ver Detalles Completos
                        </a>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Sistema Académico <?= date('Y') ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
