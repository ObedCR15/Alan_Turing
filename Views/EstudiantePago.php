<?php
session_start();

// Incluir el archivo de conexión
require_once "../Config/conexion.php";  // Asegúrate de que la ruta es correcta

// Incluir el controlador
require_once "../Controllers/EstudiantePagoController.php";  // Mantén esta referencia

// Instancia el controlador y pasa el objeto PDO
$controller = new EstudiantePagoController($pdo);
$controller->cargarPagos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Pagos - Alan Turing</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
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
                    <h1 class="mt-4">Mis Pagos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Panel Estudiante</li>
                    </ol>

                    <?php if (!empty($controller->programas)): ?>
                        <?php foreach ($controller->programas as $programaNombre => $programa): ?>
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    Programa: <?= htmlspecialchars($programaNombre) ?> 
                                    <span class="float-end">Duración: <?= htmlspecialchars($programa['duracion']) ?> meses</span>
                                </div>
                                <div class="card-body">
                                    <h5 class="text-primary">Matrícula</h5>
                                    <p><strong>Monto:</strong> S/ <?= htmlspecialchars($programa['monto_matricula']) ?></p>
                                    <p><strong>Estado:</strong> 
                                        <?= htmlspecialchars($programa['estado_matricula']) == 'Pagado' ? '<span class="text-success">Pagado</span>' : '<span class="text-danger">Pendiente</span>' ?>
                                    </p>

                                    <h5 class="text-primary mt-4">Pensiones</h5>
                                    
                                    <?php if (!empty($programa['pensiones'])): ?>
                                        <div class="list-group">
                                            <?php foreach ($programa['pensiones'] as $pension): ?>
                                                <div class="list-group-item">
                                                    <h6>Cuota <?= $pension['numero_cuota'] ?>: S/ <?= htmlspecialchars($pension['monto_pension']) ?></h6>
                                                    <p><strong>Fecha de Pago:</strong> <?= htmlspecialchars($pension['fecha_pago']) ?></p>
                                                    <p><strong>Estado:</strong> 
                                                        <?= htmlspecialchars($pension['estado_pension']) == 'Pagado' ? '<span class="text-success">Pagado</span>' : '<span class="text-danger">Pendiente</span>' ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning text-center fs-4">
                                            ❌ No tienes pensiones asociadas.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center fs-4">
                            ❌ No tienes pagos registrados.
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="dashboardEstudiante.php" class="btn btn-secondary">Volver al Panel</a>
                    </div>
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
