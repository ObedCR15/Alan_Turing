<?php
session_start();

// Incluir el archivo de conexión
require_once "../Config/conexion.php";  // Incluye correctamente la conexión PDO

// Incluir el controlador
require_once "../Controllers/EstudianteCursoController.php";  // Mantén esta referencia

// Instancia el controlador y pasa el objeto PDO
$controller = new EstudianteCursoController($pdo);
$controller->cargarCursos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Cursos - Alan Turing</title>
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
                    <h1 class="mt-4">Mis Cursos</h1>
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
                                    <h5 class="text-primary">Cursos Inscritos</h5>
                                    
                                    <?php if (!empty($programa['cursos'])): ?>
                                        <?php foreach ($programa['cursos'] as $curso): ?>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-primary"><?= htmlspecialchars($curso['nombre_curso']) ?></h5>
                                                    <p><strong>Descripción:</strong> <?= htmlspecialchars($curso['descripcion']) ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Docente a Cargo</h5>
                                                    <p><strong>Nombre:</strong> <?= htmlspecialchars($curso['docente_nombre'] . ' ' . $curso['docente_apellido']) ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="alert alert-warning text-center fs-4">
                                            ❌ No tienes cursos inscritos en este programa.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center fs-4">
                            ❌ No tienes programas o cursos inscritos.
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
