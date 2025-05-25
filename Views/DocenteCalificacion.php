<?php
session_start();
if (!isset($_SESSION['docente_id'])) {
    header('Location: ../Views/LoginDocente.php');
    exit;
}

require_once "../Config/conexion.php";
require_once "../Controllers/DocenteCalificacionController.php";
require_once "../Controllers/DocenteEliminarCalificacionController.php";

$controller = new DocenteCalificacionController($pdo, $_SESSION['docente_id']);
$controller->cargarDatos();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $eliminarController = new DocenteEliminarCalificacionController($pdo, $_SESSION['docente_id']);
    if (isset($_GET['id_curso'], $_GET['id_programa'], $_GET['descripcion'])) {
        $eliminarController->eliminarCalificacion(
            $_GET['id_curso'],
            $_GET['id_programa'],
            $_GET['descripcion']
        );
    }
    header("Location: DocenteCalificacion.php?" . http_build_query([
        'id_curso' => $_GET['id_curso'] ?? '',
        'id_programa' => $_GET['id_programa'] ?? ''
    ]));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Gestionar Calificaciones (Agrupado por Descripción)</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed bg-light">

<?php if (isset($_SESSION['mensaje'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '<?= $_SESSION['mensaje_tipo'] ?>',
            title: '<?= $_SESSION['mensaje_tipo'] === 'success' ? '¡Éxito!' : 'Error' ?>',
            text: '<?= addslashes($_SESSION['mensaje']) ?>',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
<?php
unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']);
endif;
?>

<?php include 'headerDocente.php'; ?>
<div id="layoutSidenav">
    <?php include 'sidebarDocente.php'; ?>

    <div id="layoutSidenav_content">
        <main class="container-fluid px-4 py-4">

            <h1 class="mb-4"><i class="bi bi-journal-check me-2"></i>Gestionar Calificaciones</h1>

            <form method="GET" class="row g-3 mb-4" id="formCursosProgramas">
                <div class="col-md-6">
                    <label for="id_curso" class="form-label">Curso</label>
                    <select id="id_curso" name="id_curso" class="form-select" required onchange="enviarFormularioCurso()">
                        <option value="">Seleccione un curso</option>
                        <?php
                        $cursosMostrados = [];
                        foreach ($controller->cursosProgramas as $item) {
                            if (!in_array($item['id_curso'], $cursosMostrados)) {
                                $cursosMostrados[] = $item['id_curso'];
                                $selected = ($item['id_curso'] == $controller->cursoSeleccionado) ? 'selected' : '';
                                echo "<option value='{$item['id_curso']}' $selected>" . htmlspecialchars($item['nombre_curso']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="id_programa" class="form-label">Programa</label>
                    <select id="id_programa" name="id_programa" class="form-select" required onchange="this.form.submit()" <?= empty($controller->cursoSeleccionado) ? 'disabled' : '' ?>>
                        <option value="">Seleccione un programa</option>
                        <?php
                        foreach ($controller->cursosProgramas as $item) {
                            if ($item['id_curso'] == $controller->cursoSeleccionado) {
                                $selected = ($item['id_programa'] == $controller->programaSeleccionado) ? 'selected' : '';
                                echo "<option value='{$item['id_programa']}' $selected>" . htmlspecialchars($item['nombre_programa']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </form>

            <?php if ($controller->cursoSeleccionado && $controller->programaSeleccionado): ?>
                <div class="mb-3">
                    <a href="DocenteNuevaCalificacion.php?id_curso=<?= $controller->cursoSeleccionado ?>&id_programa=<?= $controller->programaSeleccionado ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Insertar Calificación
                    </a>
                </div>

                <?php if (!empty($controller->calificaciones)): ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tablaCalificaciones">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Total Calificaciones</th>
                                            <th>Última Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($controller->calificaciones as $cal): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($cal['descripcion']) ?></td>
                                                <td><?= htmlspecialchars($cal['total_calificaciones']) ?></td>
                                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($cal['ultima_fecha']))) ?></td>
                                                <td>
                                                    <a href="DocenteModificarCalificacion.php?id_curso=<?= $controller->cursoSeleccionado ?>&id_programa=<?= $controller->programaSeleccionado ?>&descripcion=<?= urlencode($cal['descripcion']) ?>" 
                                                       class="btn btn-warning me-1 px-3 py-2" title="Modificar">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger px-3 py-2 btnEliminar" title="Eliminar" data-descripcion="<?= htmlspecialchars($cal['descripcion'], ENT_QUOTES) ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No hay calificaciones registradas para este curso y programa.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Seleccione un curso y programa para ver calificaciones.</div>
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
function enviarFormularioCurso() {
    const form = document.getElementById('formCursosProgramas');
    document.getElementById('id_programa').value = '';
    form.submit();
}

$(document).ready(function() {
    $('#tablaCalificaciones').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[2, 'desc']]
    });

    $('.btnEliminar').click(function(e) {
        e.preventDefault();
        const descripcion = $(this).data('descripcion');
        Swal.fire({
            title: '¿Eliminar calificación?',
            html: `Esta acción eliminará <b>todas las calificaciones</b> con la descripción:<br><b>${descripcion}</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash"></i> Eliminar',
            cancelButtonText: '<i class="bi bi-x-circle"></i> Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = new URL(window.location.href);
                url.searchParams.set('eliminar', '1');
                url.searchParams.set('id_curso', '<?= $controller->cursoSeleccionado ?>');
                url.searchParams.set('id_programa', '<?= $controller->programaSeleccionado ?>');
                url.searchParams.set('descripcion', descripcion);
                window.location.href = url.toString();
            }
        });
    });
});
</script>

</body>
</html>
