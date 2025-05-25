<?php
session_start();
if (!isset($_SESSION['docente_id'])) {
    header('Location: ../Views/LoginDocente.php');
    exit;
}

require_once "../Config/conexion.php";
require_once "../Controllers/DocenteRegistrarAsistenciaController.php";

$controller = new DocenteRegistrarAsistenciaController($pdo, $_SESSION['docente_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->procesarFormulario();
} else {
    $controller->cargarDatos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Asistencia</title>
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

            <h1 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Registrar Asistencia</h1>

            <?php if ($controller->mensaje): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire('Éxito', '<?= addslashes($controller->mensaje) ?>', 'success');
                    });
                </script>
            <?php endif; ?>

            <?php if ($controller->error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($controller->error) ?></div>
            <?php endif; ?>

            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="id_curso" class="form-label">Curso</label>
                        <select id="id_curso" name="id_curso" class="form-select" required onchange="this.form.submit()">
                            <option value="">-- Seleccione un curso --</option>
                            <?php foreach ($controller->cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>"
                                    <?= ($controller->cursoSeleccionado == $curso['id_curso']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($curso['nombre_curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="id_programa" class="form-label">Programa</label>
                        <select id="id_programa" name="id_programa" class="form-select" required onchange="this.form.submit()" <?= empty($controller->programas) ? 'disabled' : '' ?>>
                            <option value="">-- Seleccione un programa --</option>
                            <?php foreach ($controller->programas as $programa): ?>
                                <option value="<?= $programa['id_programa'] ?>"
                                    <?= ($controller->programaSeleccionado == $programa['id_programa']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($programa['nombre_programa']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" 
                           value="<?= htmlspecialchars($controller->fecha) ?>" required onchange="this.form.submit()" />
                    </div>
                </div>
            </form>

            <?php if ($controller->cursoSeleccionado && $controller->programaSeleccionado && !empty($controller->estudiantes)): ?>

                <form method="POST">
                    <input type="hidden" name="id_curso" value="<?= $controller->cursoSeleccionado ?>" />
                    <input type="hidden" name="id_programa" value="<?= $controller->programaSeleccionado ?>" />
                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($controller->fecha) ?>" />

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Matrícula</th>
                                    <th>Nombre Completo</th>
                                    <th>Asistencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($controller->estudiantes as $estudiante): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($estudiante['numero_matricula']) ?></td>
                                        <td><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></td>
                                        <td>
                                            <select name="asistencia[<?= $estudiante['id_estudiante'] ?>]" class="form-select" required>
                                                <option value="Presente">Presente</option>
                                                <option value="Ausente">Ausente</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Guardar Asistencia</button>
                </form>

            <?php elseif ($controller->cursoSeleccionado && $controller->programaSeleccionado): ?>
                <div class="alert alert-warning">No hay estudiantes matriculados en este programa.</div>
            <?php endif; ?>

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
