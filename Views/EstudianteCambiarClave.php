<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../Views/LoginEstudiante.php");
    exit();
}

require_once "../Config/conexion.php";
require_once "../Controllers/EstudianteCambiarClaveController.php";

$controller = new EstudianteCambiarClaveController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exito = $controller->cambiarClave($_POST, $_SESSION['student_id']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cambiar Contraseña - Estudiante</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed bg-light">

    <?php include 'headerEstudiante.php'; ?>
    <div id="layoutSidenav">
        <?php include 'sidebarEstudiante.php'; ?>

        <div id="layoutSidenav_content">
            <main class="container-fluid px-4 py-4">

                <h1 class="mb-4"><i class="bi bi-key-fill me-2"></i>Cambiar Contraseña</h1>

                <?php if (isset($exito) && $exito): ?>
                    <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '<?= htmlspecialchars($controller->getMensaje()) ?>',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'DashboardEstudiante.php';
                    });
                    </script>
                <?php elseif ($controller->getError()): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($controller->getError()) ?></div>
                <?php endif; ?>

                <form id="formCambiarClave" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="clave_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" id="clave_actual" name="clave_actual" class="form-control" required autofocus />
                    </div>

                    <div class="mb-3">
                        <label for="nueva_clave" class="form-label">Nueva Contraseña</label>
                        <input type="password" id="nueva_clave" name="nueva_clave" class="form-control" required maxlength="20" />
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_clave" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirmar_clave" name="confirmar_clave" class="form-control" required maxlength="20" />
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                    </button>
                </form>
            </main>
        </div>
    </div>

<script>
document.getElementById('formCambiarClave').addEventListener('submit', function(event) {
    const nuevaClave = document.getElementById('nueva_clave').value.trim();
    const confirmarClave = document.getElementById('confirmar_clave').value.trim();

    if (nuevaClave.length < 4) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La nueva contraseña debe tener al menos 4 caracteres.',
        });
        return;
    }

    if (nuevaClave !== confirmarClave) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La nueva contraseña y la confirmación no coinciden.',
        });
        return;
    }
});
</script>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
