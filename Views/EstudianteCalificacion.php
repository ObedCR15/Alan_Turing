<?php
session_start();
require_once "../Config/conexion.php";
require_once "../Controllers/EstudianteCalificacionController.php";

$controller = new EstudianteCalificacionController($pdo);
$controller->cargarCalificaciones();

// Para mostrar detalle asistencias por curso
foreach ($controller->programas as $idPrograma => &$programa) {
    foreach ($programa['cursos'] as $idCurso => &$curso) {
        $curso['detalle_asistencias'] = $controller->obtenerDetalleFaltas($_SESSION['student_id'], $idCurso);
    }
}

unset($programa, $curso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Mis Calificaciones - Alan Turing</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="../assets/css/styles.css" rel="stylesheet" />
<link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
<style>
    .card-curso {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    .card-curso:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .progress-bar-custom {
        height: 25px;
        border-radius: 12px;
    }
    .badge-promedio {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
    }
    .list-group-item {
        padding: 10px;
    }
</style>
</head>
<body class="sb-nav-fixed">
<?php include 'headerEstudiante.php'; ?>
<div id="layoutSidenav">
    <?php include 'sidebarEstudiante.php'; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Mis Calificaciones</h1>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php if (!empty($controller->programas)): ?>
                            <div class="row g-4">
                                <?php foreach ($controller->programas as $programa): ?>
                                    <div class="col-12">
                                        <div class="card card-curso shadow-sm">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi bi-mortarboard me-2"></i>
                                                    <?= htmlspecialchars($programa['nombre_programa']) ?>
                                                </div>
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-clock-history me-2"></i>
                                                    <?= htmlspecialchars($programa['duracion']) ?> meses
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row row-cols-1 row-cols-md-2 g-4">
                                                    <?php foreach ($programa['cursos'] as $curso): ?>
                                                        <div class="col">
                                                            <div class="card h-100 border-0 shadow-sm">
                                                                <div class="card-body">
                                                                    <h5 class="card-title text-primary mb-4">
                                                                        <i class="bi bi-book me-2"></i>
                                                                        <?= htmlspecialchars($curso['nombre_curso']) ?>
                                                                    </h5>
                                                                    <div class="d-flex align-items-center mb-4">
                                                                        <div class="bg-primary p-2 rounded-circle me-3">
                                                                            <i class="bi bi-person-fill text-white"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted d-block">Docente a cargo</small>
                                                                            <span class="fw-bold"><?= htmlspecialchars($curso['docente_nombre'] . ' ' . $curso['docente_apellido']) ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <div class="bg-light p-3 rounded">
                                                                                <h6 class="text-success mb-3">
                                                                                    <i class="bi bi-clipboard-data me-2"></i>
                                                                                    Rendimiento Académico
                                                                                </h6>
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <span>Promedio:</span>
                                                                                    <span class="badge bg-success badge-promedio">
                                                                                        <?= htmlspecialchars($curso['promedio_notas']) ?>
                                                                                    </span>
                                                                                </div>
                                                                                <small class="text-muted">
                                                                                    <?= htmlspecialchars($curso['cantidad_notas']) ?> evaluaciones registradas
                                                                                </small>
                                                                                <ul class="list-group mt-3">
                                                                                    <?php foreach ($curso['calificaciones'] as $calificacion): ?>
                                                                                        <li class="list-group-item">
                                                                                            <strong>Nota:</strong> <?= htmlspecialchars($calificacion['calificacion']) ?><br>
                                                                                            <strong>Descripción:</strong> <?= htmlspecialchars($calificacion['descripcion']) ?>
                                                                                        </li>
                                                                                    <?php endforeach; ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="bg-light p-3 rounded">
                                                                                <h6 class="text-info mb-3">
                                                                                    <i class="bi bi-check2-circle me-2"></i>
                                                                                    Asistencia
                                                                                </h6>
                                                                                <div class="progress progress-bar-custom mb-2">
                                                                                    <div class="progress-bar bg-info"
                                                                                         style="width: <?= htmlspecialchars($curso['promedio_asistencia']) ?>%"
                                                                                         role="progressbar"
                                                                                         aria-valuenow="<?= htmlspecialchars($curso['promedio_asistencia']) ?>"
                                                                                         aria-valuemin="0" aria-valuemax="100">
                                                                                        <?= htmlspecialchars($curso['promedio_asistencia']) ?>%
                                                                                    </div>
                                                                                </div>
                                                                                <small class="text-muted">
                                                                                    <?= htmlspecialchars($curso['cantidad_asistencias']) ?> registros de asistencia
                                                                                </small>
                                                                                <!-- Mostrar detalle de asistencias -->
                                                                                <h6 class="mt-3">Faltas registradas:</h6>
                                                                                <ul>
                                                                                <?php if (!empty($curso['detalle_asistencias'])): ?>
                                                                                    <?php foreach ($curso['detalle_asistencias'] as $falta): ?>
                                                                                        <li>
                                                                                            <?= htmlspecialchars(date('d-m-Y', strtotime($falta['fecha']))) ?>: 
                                                                                            <?= htmlspecialchars($falta['estado']) ?>
                                                                                        </li>
                                                                                    <?php endforeach; ?>
                                                                                <?php else: ?>
                                                                                    <li>No hay faltas registradas.</li>
                                                                                <?php endif; ?>
                                                                                </ul>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center py-5">
                                <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
                                <h4>No se encontraron calificaciones</h4>
                                <p class="mb-0">Por favor contacta con tu coordinador académico</p>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-4">
                            <a href="dashboardEstudiante.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle me-2"></i>Volver al Panel
                            </a>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
