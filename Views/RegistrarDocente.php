<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/RegistrarDocenteController.php');

$controller = new RegistrarDocenteController($pdo);
$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $controller->registrarDocente($_POST, $error);
    if ($success) {
        $_SESSION['mensaje'] = "Docente registrado exitosamente.";
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
  <title>Registrar Docente</title>
  <link href="../assets/css/styles.css" rel="stylesheet" />
  <script src="../assets/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
<?php include('header.php'); ?>
<div id="layoutSidenav">
  <?php include('sidebar.php'); ?>
  <div id="layoutSidenav_content">
    <main class="container-fluid px-4">
      <h1 class="mt-4">Registrar Docente</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="GestionarDocente.php">Docentes</a></li>
        <li class="breadcrumb-item active">Registrar</li>
      </ol>

      <?php if ($error): ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?= json_encode($error) ?>
          });
        </script>
      <?php endif; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Especialidad</label>
          <input type="text" name="especialidad" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">DNI</label>
          <input type="text" name="DNI" id="dni" class="form-control" required oninput="document.getElementById('clave').value = this.value">
        </div>
        <div class="col-md-4">
          <label class="form-label">Celular</label>
          <input type="text" name="celular" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Edad</label>
          <input type="number" name="edad" class="form-control">
        </div>
        <div class="col-md-12">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contraseña (DNI)</label>
          <input type="text" name="clave" id="clave" class="form-control" readonly>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
          <a href="GestionarDocente.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </main>
  </div>
</div>
</body>
</html>
