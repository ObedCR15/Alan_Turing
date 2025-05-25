<?php
session_start();
require_once "../Controllers/EstudianteMatriculaController.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Constancia de Matrícula - Alan Turing</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="sb-nav-fixed bg-light">

    <!-- Header -->
    <?php include 'headerEstudiante.php'; ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include 'sidebarEstudiante.php'; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 py-4">

                    <h1 class="mt-4">Constancia de Matrícula</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Panel Estudiante</li>
                    </ol>

                    <?php if ($controlador->estado === 'matriculado' && $controlador->datosMatricula): ?>
                        <!-- Mostrar los datos completos del estudiante -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                Datos del Estudiante
                            </div>
                            <div class="card-body">
                                <p><strong>Nombre Completo:</strong> <?= htmlspecialchars($controlador->datosMatricula['nombre'] . ' ' . $controlador->datosMatricula['apellido']) ?></p>
                                <p><strong>Edad:</strong> <?= htmlspecialchars($controlador->datosMatricula['edad']) ?> años</p>
                                <p><strong>DNI:</strong> <?= htmlspecialchars($controlador->datosMatricula['DNI']) ?></p>
                                <p><strong>Programa:</strong> <?= htmlspecialchars($controlador->datosMatricula['nombre_programa']) ?></p>
                                <p><strong>Fecha de Matrícula:</strong> <?= date('d/m/Y', strtotime($controlador->datosMatricula['fecha_matricula'])) ?></p>
                                <p><strong>Monto Pagado:</strong> S/ <?= number_format($controlador->datosMatricula['monto_matricula'], 2) ?></p>
                                <p><strong>Estado Matrícula:</strong> <?= htmlspecialchars($controlador->datosMatricula['estado_matricula']) ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger text-center fs-4">
                            ❌ No estás matriculado actualmente.
                        </div>
                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
