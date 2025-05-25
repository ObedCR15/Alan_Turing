<?php
session_start();
if (!isset($_SESSION['docente_id'])) {
    header('Location: ../Views/LoginDocente.php');
    exit;
}

require_once "../Config/conexion.php";
require_once "../Controllers/DocenteModificarAsistenciaController.php";

$controller = new DocenteModificarAsistenciaController($pdo, $_SESSION['docente_id']);
$controller->cargarDatos();
$controller->modificarAsistencia();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modificar Asistencia</title>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0"><i class="bi bi-check2-square me-2"></i>Modificar Asistencia</h1>
                </div>

                <!-- Mostrar mensaje de éxito si está presente -->
                <?php if ($controller->mensaje): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '<?= htmlspecialchars($controller->mensaje) ?>',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirige a la página de DocenteAsistencia después de la notificación
                            window.location.href = 'DocenteAsistencia.php';
                        });
                    </script>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <?php foreach ($controller->estudiantes as $estudiante): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></h5>
                                        <p class="card-text">Estado de asistencia: <?= htmlspecialchars($estudiante['estado']) ?></p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $estudiante['id_estudiante'] ?>]" value="Presente" <?= $estudiante['estado'] == 'Presente' ? 'checked' : '' ?>>
                                            <label class="form-check-label">Presente</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="asistencia[<?= $estudiante['id_estudiante'] ?>]" value="Ausente" <?= $estudiante['estado'] == 'Ausente' ? 'checked' : '' ?>>
                                            <label class="form-check-label">Ausente</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Guardar Cambios</button>
                </form>

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
