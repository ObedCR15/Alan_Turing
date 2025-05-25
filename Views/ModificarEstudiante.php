<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/ModificarEstudianteController.php');

$controller = new ModificarEstudianteController($pdo);

// Verifica si se recibió el ID
if (!isset($_GET['id'])) {
    header('Location: GestionarEstudiantes.php');
    exit();
}

$id = $_GET['id'];
$estudiante = $controller->obtenerEstudiante($id);

if (!$estudiante) {
    $_SESSION['mensaje'] = "Estudiante no encontrado.";
    header("Location: GestionarEstudiantes.php");
    exit();
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['restablecer_clave'])) {
        // Restablecer contraseña
        $exito = $controller->restablecerClave($_POST['id_estudiante']);
        $_SESSION['mensaje'] = $exito ? "Contraseña restablecida al DNI." : "Error al restablecer contraseña.";
    } else {
        $actualizado = $controller->actualizarEstudiante($_POST);
        $_SESSION['mensaje'] = $actualizado ? "Estudiante actualizado exitosamente." : "Error al actualizar estudiante.";
    }

    // Mostrar mensaje y redirigir
    header("Location: GestionarEstudiantes.php"); // Redirige después de guardar los cambios
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Modificar Estudiante</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/styles.css" rel="stylesheet" />
  <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="../assets/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
  <?php include('header.php'); ?>
  <div id="layoutSidenav">
    <?php include('sidebar.php'); ?>
    <div id="layoutSidenav_content">
      <main class="container-fluid px-4">
        <h1 class="mt-4">Modificar Estudiante</h1>
        <ol class="breadcrumb mb-4">
          <li class="breadcrumb-item"><a href="GestionarEstudiantes.php">Estudiantes</a></li>
          <li class="breadcrumb-item active">Editar</li>
        </ol>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($_SESSION['mensaje'])): ?>
          <script>
            Swal.fire({
                icon: '<?= strpos($_SESSION['mensaje'], 'Error') !== false ? 'error' : 'success' ?>',
                title: '<?= strpos($_SESSION['mensaje'], 'Error') !== false ? '¡Error!' : '¡Éxito!' ?>',
                text: '<?= $_SESSION['mensaje'] ?>',
                timer: 3000,
                showConfirmButton: false
            });
          </script>
          <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="card mb-4">
          <div class="card-header"><i class="fas fa-user-edit me-1"></i> Formulario de Edición</div>
          <div class="card-body">
            <form method="POST">
              <input type="hidden" name="id_estudiante" value="<?= $estudiante['id_estudiante'] ?>">

              <!-- Botón para restablecer la clave al DNI (colocado en la parte superior) -->
              <div class="text-center mb-3">
                <button type="submit" name="restablecer_clave" class="btn btn-warning">
                  <i class="fas fa-sync-alt me-2"></i>Restablecer Clave (DNI)
                </button>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label>Nombre</label>
                  <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($estudiante['nombre']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Apellido</label>
                  <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($estudiante['apellido']) ?>" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label>DNI</label>
                  <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($estudiante['DNI']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Edad</label>
                  <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($estudiante['edad']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Celular</label>
                  <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($estudiante['celular']) ?>" required>
                </div>
              </div>

              <div class="mb-3">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($estudiante['direccion']) ?>" required>
              </div>

              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($estudiante['email']) ?>" required>
              </div>

              <!-- Botón para actualizar los datos del estudiante -->
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="GestionarEstudiantes.php" class="btn btn-secondary">
                  <i class="fas fa-times-circle me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
