<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/ModificarDocenteController.php');

$controller = new ModificarDocenteController($pdo);

if (!isset($_GET['id'])) {
    header("Location: GestionarDocente.php");
    exit();
}

$id = (int)$_GET['id'];
$docente = $controller->obtenerDocente($id);

if (!$docente) {
    $_SESSION['mensaje'] = "Docente no encontrado.";
    header("Location: GestionarDocente.php");
    exit();
}

$mensajeExito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['restablecer_clave'])) {
        $exito = $controller->restablecerClave((int)$_POST['id_docente']);
        $mensajeExito = $exito ? "Contraseña restablecida al DNI." : "Error al restablecer contraseña.";
    } else {
        $actualizado = $controller->actualizarDocente($_POST);
        $_SESSION['mensaje'] = $actualizado ? "Docente actualizado exitosamente." : "Error al actualizar docente.";
        header("Location: GestionarDocente.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Modificar Docente</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/styles.css" rel="stylesheet" />
  <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
<?php include('header.php'); ?>
<div id="layoutSidenav">
  <?php include('sidebar.php'); ?>
  <div id="layoutSidenav_content">
    <main class="container-fluid px-4">
      <h1 class="mt-4">Modificar Docente</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="GestionarDocente.php">Docentes</a></li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>

      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user-edit me-1"></i> Formulario de Edición</div>
        <div class="card-body">
          <!-- Botón "Restablecer Clave (DNI)" en la parte superior -->
          <form method="POST" action="">
            <input type="hidden" name="id_docente" value="<?= htmlspecialchars($docente['id_docente']) ?>">

            <div class="text-center mb-3">
              <button type="submit" name="restablecer_clave" class="btn btn-warning">Restablecer Clave (DNI)</button>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($docente['nombre']) ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($docente['apellido']) ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($docente['email']) ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Especialidad</label>
                <input type="text" name="especialidad" class="form-control" value="<?= htmlspecialchars($docente['especialidad']) ?>">
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label>DNI</label>
                <input type="text" name="DNI" class="form-control" value="<?= htmlspecialchars($docente['DNI']) ?>" required>
              </div>
              <div class="col-md-4 mb-3">
                <label>Celular</label>
                <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($docente['celular']) ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label>Edad</label>
                <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($docente['edad']) ?>">
              </div>
            </div>

            <div class="mb-3">
              <label>Dirección</label>
              <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($docente['direccion']) ?>">
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-success">Actualizar Docente</button>
              <a href="GestionarDocente.php" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>

          <!-- Mostrar mensaje de éxito si se realizó la acción -->
          <?php if ($mensajeExito): ?>
            <script>
              Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= $mensajeExito ?>',
                timer: 3000,
                showConfirmButton: false
              });
            </script>
          <?php endif; ?>
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
