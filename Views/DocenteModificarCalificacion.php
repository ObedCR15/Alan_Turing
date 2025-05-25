<?php
session_start();
require_once "../Config/conexion.php";
require_once "../Controllers/DocenteModificarCalificacionController.php";

$controller = new DocenteModificarCalificacionController($pdo, $_SESSION['docente_id']);
$controller->manejarRequest();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Modificar Calificación</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed bg-light">

<?php if (!empty($controller->mensaje)): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: <?= json_encode($controller->mensaje) ?>,
        timer: 3000,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'DocenteCalificacion.php';
    });
</script>
<?php endif; ?>

<?php include 'headerDocente.php'; ?>
<div id="layoutSidenav">
    <?php include 'sidebarDocente.php'; ?>
    <div id="layoutSidenav_content">
        <main class="container-fluid p-4">
            <h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Modificar Calificaciones</h2>

            <?php if ($controller->getError()): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($controller->getError()) ?></div>
            <?php endif; ?>

            <?php if ($controller->getDescripcion()): ?>
                <form method="POST">
                    <input type="hidden" name="id_curso" value="<?= htmlspecialchars($_GET['id_curso']) ?>">
                    <input type="hidden" name="id_programa" value="<?= htmlspecialchars($_GET['id_programa']) ?>">
                    <input type="hidden" name="descripcion" value="<?= htmlspecialchars($controller->getDescripcion()) ?>">

                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes y Calificaciones
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
                                        <?php foreach ($controller->getCalificaciones() as $registro): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($registro['numero_matricula']) ?></td>
                                                <td><?= htmlspecialchars($registro['nombre'] . ' ' . $registro['apellido']) ?></td>
                                                <td>
                                                    <input type="number" name="notas[<?= $registro['id_estudiante'] ?>]"
                                                        class="form-control" min="0" max="20" step="0.01"
                                                        value="<?= htmlspecialchars($registro['calificacion']) ?>" required>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 mb-4">
                                <label class="form-label">Descripción:</label>
                                <input type="text" name="descripcion_nueva" class="form-control" 
                                    value="<?= htmlspecialchars($controller->getDescripcion()) ?>" required />
                            </div>

                            <button type="submit" name="modificarNotas" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>Actualizar Calificaciones
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-info">No hay datos para mostrar.</div>
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
