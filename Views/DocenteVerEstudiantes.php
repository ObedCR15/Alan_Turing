<?php
session_start();
if (!isset($_SESSION['docente_id'])) {
    $_SESSION['mensaje'] = 'Por favor, inicia sesión para continuar.';
    $_SESSION['mensaje_tipo'] = 'error';
    header('Location: ../Views/LoginDocente.php');
    exit;
}

require_once "../Config/conexion.php";
require_once "../Controllers/DocenteVerEstudiantesController.php";

$controller = new DocenteVerEstudiantesController($pdo, $_SESSION['docente_id']);
$controller->cargarDatos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Estudiantes Matriculados</title>
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

            <?php if (isset($_SESSION['mensaje'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?= $_SESSION['mensaje_tipo'] ?>',
                            title: '<?= $_SESSION['mensaje_tipo'] === 'success' ? 'Éxito!' : 'Error' ?>',
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

            <h1 class="mb-4"><i class="bi bi-people-fill me-2"></i>Estudiantes Matriculados</h1>

            <form method="GET" class="mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Seleccione un curso
                    </div>
                    <div class="card-body">
                        <select name="id_curso" class="form-select" required onchange="this.form.submit()">
                            <option value="">-- Seleccione un curso --</option>
                            <?php foreach ($controller->cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>"
                                    <?= (isset($_GET['id_curso']) && $_GET['id_curso'] == $curso['id_curso']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>

            <?php if (isset($_GET['id_curso'])): ?>
                <?php if (!empty($controller->estudiantes)): ?>
                    <?php foreach ($controller->estudiantes as $programa => $estudiantesPrograma): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header bg-secondary text-white">
                                Programa: <?= htmlspecialchars($programa) ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Matrícula</th>
                                                <th>Nombre Completo</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($estudiantesPrograma as $estudiante): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($estudiante['numero_matricula']) ?></td>
                                                    <td><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></td>
                                                    <td><?= htmlspecialchars($estudiante['email']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning">No hay estudiantes matriculados en este curso.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Por favor, seleccione un curso para ver los estudiantes matriculados.</div>
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
        // Inicializa DataTables para cada tabla individual
        $('table.table-hover').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
</body>
</html>
