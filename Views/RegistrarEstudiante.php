<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/RegistrarEstudianteController.php');

$controller = new RegistrarEstudianteController($pdo);
$numero_matricula = $controller->generarNumeroMatricula();

$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'numero_matricula' => $_POST['numero_matricula'],
        'nombre'           => $_POST['nombre'],
        'apellido'         => $_POST['apellido'],
        'DNI'              => $_POST['DNI'],
        'edad'             => $_POST['edad'],
        'direccion'        => $_POST['direccion'],
        'celular'          => $_POST['celular'],
        'email'            => $_POST['email'],
        'clave'            => $_POST['clave']  // igual al DNI
    ];

    $registrado = $controller->registrar($datos, $error);
    $_SESSION['mensaje'] = $registrado 
        ? 'Estudiante registrado exitosamente'
        : 'Error al registrar estudiante';

    header("Location: GestionarEstudiantes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Estudiante</title>
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
      <h1 class="mt-4">Registrar Estudiante</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="GestionarEstudiantes.php">Estudiantes</a></li>
        <li class="breadcrumb-item active">Registrar</li>
      </ol>

      <?php if ($mensaje): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
              icon: <?= strpos($mensaje, 'exitosamente') !== false ? "'success'" : "'error'" ?>,
              title: <?= strpos($mensaje, 'exitosamente') !== false ? "'¡Éxito!'" : "'Error'" ?>,
              text: <?= json_encode($mensaje) ?>,
              timer: 2500,
              showConfirmButton: false
            });
          });
        </script>
      <?php endif; ?>

      <form method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">N° Matrícula</label>
          <input type="text" name="numero_matricula" class="form-control" value="<?= $numero_matricula ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">DNI</label>
          <input type="text" name="DNI" class="form-control" id="dni" required oninput="document.getElementById('clave').value = this.value">
        </div>
        <div class="col-md-3">
          <label class="form-label">Edad</label>
          <input type="number" name="edad" class="form-control" required>
        </div>
        <div class="col-md-9">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Celular</label>
          <input type="text" name="celular" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Clave (DNI)</label>
          <input type="text" name="clave" id="clave" class="form-control" readonly>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
          <a href="GestionarEstudiantes.php" class="btn btn-secondary">Cancelar</a>
        </div>
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
