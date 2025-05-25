<?php
session_start();
if (!isset($_SESSION['docente_id'])) {
    header('Location: ../Views/LoginDocente.php');
    exit;
}

require_once "../Config/conexion.php";
require_once "../Controllers/DocenteAsistenciaController.php";
require_once "../Controllers/DocenteEliminarAsistenciaController.php";

// Cargar datos principales
$asistenciaController = new DocenteAsistenciaController($pdo, $_SESSION['docente_id']);
$asistenciaController->cargarDatos();

// Manejar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $eliminarController = new DocenteEliminarAsistenciaController($pdo);
    
    if (isset($_GET['id_curso'], $_GET['id_programa'], $_GET['fecha'])) {
        $eliminarController->eliminarAsistencia(
            $_GET['id_curso'],
            $_GET['id_programa'],
            $_GET['fecha']
        );
    }
    
    // Redirigir para evitar reenvío de formulario
    header("Location: DocenteAsistencia.php?" . http_build_query([
        'id_curso' => $_GET['id_curso'] ?? '',
        'id_programa' => $_GET['id_programa'] ?? ''
    ]));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencia</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed bg-light">

<?php include 'headerDocente.php'; ?>

<div id="layoutSidenav">
    <?php include 'sidebarDocente.php'; ?>

    <div id="layoutSidenav_content">
        <main class="container-fluid px-4 py-4">
            
            <!-- Sistema de mensajes -->
            <?php if (isset($_SESSION['mensaje'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?= $_SESSION['mensaje_tipo'] ?>',
                            title: '<?= $_SESSION['mensaje_tipo'] === 'success' ? 'Éxito' : 'Error' ?>',
                            text: '<?= addslashes($_SESSION['mensaje']) ?>',
                            timer: 3000
                        });
                    });
                </script>
                <?php
                    unset($_SESSION['mensaje']);
                    unset($_SESSION['mensaje_tipo']);
                ?>
            <?php endif; ?>

            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0"><i class="bi bi-check2-square me-2"></i>Gestión de Asistencia</h1>
                <a href="DocenteRegistrarAsistencia.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Añadir Asistencia
                </a>
            </div>

            <!-- Filtros -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_curso" class="form-label">Curso</label>
                                <select id="id_curso" name="id_curso" class="form-select" required onchange="this.form.submit()">
                                    <option value="">-- Seleccione un curso --</option>
                                    <?php foreach ($asistenciaController->cursos as $curso): ?>
                                        <option value="<?= $curso['id_curso'] ?>"
                                            <?= ($asistenciaController->cursoSeleccionado == $curso['id_curso']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($curso['nombre_curso']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="id_programa" class="form-label">Programa</label>
                                <select id="id_programa" name="id_programa" class="form-select" required 
                                    onchange="this.form.submit()" <?= empty($asistenciaController->programas) ? 'disabled' : '' ?>>
                                    <option value="">-- Seleccione un programa --</option>
                                    <?php foreach ($asistenciaController->programas as $programa): ?>
                                        <option value="<?= $programa['id_programa'] ?>"
                                            <?= ($asistenciaController->programaSeleccionado == $programa['id_programa']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($programa['nombre_programa']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listado de asistencias -->
            <?php if ($asistenciaController->cursoSeleccionado && $asistenciaController->programaSeleccionado): ?>
                <?php if (!empty($asistenciaController->fechas)): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tablaAsistencias">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Programa</th>
                                            <th>Total Estudiantes</th>
                                            <th>Presentes</th>
                                            <th>Porcentaje</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($asistenciaController->fechas as $fecha): ?>
                                            <?php foreach ($asistenciaController->resumenes[$fecha] as $resumen): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($fecha) ?></td>
                                                    <td><?= htmlspecialchars($resumen['nombre_programa']) ?></td>
                                                    <td><?= $resumen['total_estudiantes'] ?></td>
                                                    <td><?= $resumen['presentes'] ?></td>
                                                    <td><?= round(($resumen['presentes'] / max($resumen['total_estudiantes'], 1)) * 100, 2) ?>%</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="DocenteModificarAsistencia.php?id_curso=<?= $asistenciaController->cursoSeleccionado ?>&id_programa=<?= $asistenciaController->programaSeleccionado ?>&fecha=<?= $fecha ?>" 
                                                               class="btn btn-sm btn-warning">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-danger" 
                                                                    onclick="confirmarEliminacion('<?= $asistenciaController->cursoSeleccionado ?>', '<?= $asistenciaController->programaSeleccionado ?>', '<?= $fecha ?>')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning shadow-sm">
                        <i class="bi bi-exclamation-circle me-2"></i>No hay registros de asistencia
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info shadow-sm">
                    <i class="bi bi-info-circle me-2"></i>Seleccione un curso y programa
                </div>
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaAsistencias').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            order: [[0, 'desc']]
        });
    });

    function confirmarEliminacion(idCurso, idPrograma, fecha) {
        Swal.fire({
            title: '¿Eliminar asistencia?',
            html: `Esta acción eliminará <b>todas las asistencias</b><br>registradas para el ${fecha}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash"></i> Eliminar',
            cancelButtonText: '<i class="bi bi-x-circle"></i> Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `DocenteAsistencia.php?eliminar=true&id_curso=${idCurso}&id_programa=${idPrograma}&fecha=${fecha}`;
            }
        });
    }
</script>
</body>
</html>