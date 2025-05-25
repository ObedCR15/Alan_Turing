<?php
session_start();
require_once "../Config/conexion.php";
require_once "../Controllers/DashboardDocenteController.php";

$controller = new DashboardDocenteController($pdo);
$resumenDocente = $controller->cargarResumenDocente();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Docente - Alan Turing</title>

    <!-- CSS Bootstrap y estilos -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        .card-cursos {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }
        .card-estudiantes {
            background: linear-gradient(135deg, #10b981, #34d399);
        }
        .card-notas {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
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
    </style>
</head>
<body class="sb-nav-fixed">

    <!-- Header -->
    <?php include 'headerDocente.php'; ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include 'sidebarDocente.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 py-4">
                    <div class="welcome-message">
                        <h2 class="mb-3">¡Hola, <?= htmlspecialchars($_SESSION['docente_name']) ?>! 👨‍🏫</h2>
                        <p class="mb-0">Resumen de tu gestión académica actualizado al <?= date('d/m/Y') ?></p>
                    </div>

                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <!-- Cursos impartidos -->
                        <div class="col">
                            <div class="card card-dashboard card-cursos">
                                <div class="card-body text-center">
                                    <i class="bi bi-journal-bookmark card-icon"></i>
                                    <h5 class="card-title">Cursos Impartidos</h5>
                                    <p class="card-text"><?= $resumenDocente['totalCursos'] ?></p>
                                    <a href="GestionarCursos.php" class="dashboard-btn">
                                        <i class="bi bi-arrow-right me-2"></i>Ver Cursos
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Estudiantes a cargo -->
                        <div class="col">
                            <div class="card card-dashboard card-estudiantes">
                                <div class="card-body text-center">
                                    <i class="bi bi-people card-icon"></i>
                                    <h5 class="card-title">Estudiantes a Cargo</h5>
                                    <p class="card-text"><?= $resumenDocente['totalEstudiantes'] ?></p>
                                    <a href="VerEstudiantes.php" class="dashboard-btn">
                                        <i class="bi bi-arrow-right me-2"></i>Ver Estudiantes
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Promedio de notas -->
                        <div class="col">
                            <div class="card card-dashboard card-notas">
                                <div class="card-body text-center">
                                    <i class="bi bi-bar-chart-line card-icon"></i>
                                    <h5 class="card-title">Promedio de Notas</h5>
                                    <p class="card-text"><?= number_format($resumenDocente['promedioNotas'], 2) ?></p>
                                    <a href="GestionarNotas.php" class="dashboard-btn">
                                        <i class="bi bi-arrow-right me-2"></i>Ver Notas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Sistema Académico <?= date('Y') ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JS assets -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
    
</body>
</html>
