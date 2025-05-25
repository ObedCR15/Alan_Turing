<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/RegistrarCursoController.php');

$controller = new RegistrarCursoController($pdo);
$mensaje = '';
$docentes = $controller->obtenerDocentes();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $id_docente = $_POST['id_docente'];
    $mensaje = $controller->registrarCurso($nombre, $descripcion, $id_docente);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrar Curso</title>
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
      <h1 class="mt-4">Registrar Curso</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Registrar Curso</li>
      </ol>

      <?php if ($mensaje): ?>
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
              icon: <?= stripos($mensaje, 'exitosamente') !== false ? "'success'" : "'error'" ?>,
              title: <?= stripos($mensaje, 'exitosamente') !== false ? "'¡Éxito!'" : "'Error'" ?>,
              text: <?= json_encode($mensaje) ?>,
              timer: 2500,
              showConfirmButton: false
            }).then(function () {
              window.location.href = "GestionarCurso.php"; // Redirige a la página de gestión de cursos
            });
          });
        </script>
      <?php endif; ?>

      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-plus me-1"></i> Nuevo Curso</div>
        <div class="card-body">
          <form method="POST">
            <div class="form-group mb-3">
              <label for="nombre">Nombre del Curso</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group mb-3">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <div class="form-group mb-3">
              <label for="id_docente">Docente Responsable</label>
              <select class="form-control" id="id_docente" name="id_docente" required>
                <option value="">Seleccione un docente</option>
                <?php foreach ($docentes as $docente): ?>
                  <option value="<?= $docente['id_docente'] ?>">
                    <?= $docente['nombre'] . ' ' . $docente['apellido'] . ' - Especialidad: ' . $docente['especialidad'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Curso</button>
            <a href="GestionarCurso.php" class="btn btn-secondary">Cancelar</a>
          </form>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
