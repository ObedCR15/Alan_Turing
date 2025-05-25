<?php
session_start();
require_once "../Config/conexion.php";
require_once "../Controllers/DocenteNuevaCalificacionController.php";

$controller = new DocenteNuevaCalificacionController($pdo, $_SESSION['docente_id']);

// Cargar curso/programa/estudiantes segun GET (filtros)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_curso'], $_GET['id_programa'])) {
        $_POST['id_curso'] = $_GET['id_curso'];
        $_POST['id_programa'] = $_GET['id_programa'];
        $controller->manejarRequest(); // Esto cargará estudiantes si aplica
    }
} else {
    // POST para guardar notas
    $controller->manejarRequest();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nueva Calificación</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="sb-nav-fixed bg-light">

<?php include 'headerDocente.php'; ?>

<div id="layoutSidenav">
    <?php include 'sidebarDocente.php'; ?>

    <div id="layoutSidenav_content">
        <main class="container-fluid p-4">
            <h2 class="mb-4"><i class="bi bi-journal-plus me-2"></i>Nueva Calificación</h2>

            <?php if ($controller->getError()): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($controller->getError()) ?></div>
            <?php endif; ?>

            <!-- FORM de filtros: curso y programa -->
            <form method="GET" class="mb-4">
                <div class="card mb-4 shadow">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-book me-2"></i>Selección de Curso y Programa
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <!-- Selector de Curso -->
                            <div class="col-md-6">
                                <label class="form-label">Curso:</label>
                                <select name="id_curso" class="form-select" required onchange="this.form.submit()">
                                    <option value="">Seleccione un curso</option>
                                    <?php
                                    $cursosMostrados = [];
                                    foreach ($controller->getCursos() as $curso) {
                                        if (!in_array($curso['id_curso'], $cursosMostrados)) {
                                            $cursosMostrados[] = $curso['id_curso'];
                                            ?>
                                            <option value="<?= $curso['id_curso'] ?>"
                                                <?= ($curso['id_curso'] == ($_GET['id_curso'] ?? null)) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Selector de Programa -->
                            <div class="col-md-6">
                                <label class="form-label">Programa:</label>
                                <select name="id_programa" class="form-select" required onchange="this.form.submit()" <?= empty($_GET['id_curso']) ? 'disabled' : '' ?>>
                                    <option value="">Seleccione un programa</option>
                                    <?php
                                    foreach ($controller->getCursos() as $curso) {
                                        if ($curso['id_curso'] == ($_GET['id_curso'] ?? null)) {
                                            ?>
                                            <option value="<?= $curso['id_programa'] ?>"
                                                <?= ($curso['id_programa'] == ($_GET['id_programa'] ?? null)) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($curso['nombre_programa']) ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

            <!-- FORM para guardar notas -->
            <?php if (!empty($_GET['id_curso']) && !empty($_GET['id_programa']) && !empty($controller->getEstudiantes())): ?>
                <form method="POST">
                    <input type="hidden" name="id_curso" value="<?= htmlspecialchars($_GET['id_curso']) ?>">
                    <input type="hidden" name="id_programa" value="<?= htmlspecialchars($_GET['id_programa']) ?>">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Estudiante</th>
                                            <th width="200">Calificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($controller->getEstudiantes() as $estudiante): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($estudiante['numero_matricula']) ?></td>
                                                <td><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></td>
                                                <td>
                                                    <input type="number" name="notas[<?= $estudiante['id_estudiante'] ?>]" 
                                                        class="form-control" min="0" max="20" step="0.01" required>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 mb-4">
                                <label class="form-label">Descripción:</label>
                                <textarea name="descripcion" class="form-control" rows="2" required><?= htmlspecialchars($controller->getDescripcion()) ?></textarea>
                            </div>
                            <button type="submit" name="guardarNotas" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar Calificaciones
                            </button>
                        </div>
                    </div>
                </form>
            <?php elseif (!empty($_GET['id_curso']) && !empty($_GET['id_programa'])): ?>
                <div class="alert alert-warning mt-4">No hay estudiantes matriculados en este curso y programa</div>
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
