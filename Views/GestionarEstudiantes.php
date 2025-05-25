<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = require_once('../Config/conexion.php');
require_once('../Models/GestionarEstudianteModel.php');
require_once('../Controllers/EliminarEstudianteController.php');

$model = new GestionarEstudianteModel($pdo);
$eliminarController = new EliminarEstudianteController($pdo);

// Si se solicita eliminar
if (isset($_GET['eliminar'])) {
    $eliminado = $eliminarController->eliminarEstudiante($_GET['eliminar']);
    $_SESSION['mensaje'] = $eliminado 
        ? "Estudiante eliminado exitosamente." 
        : "Error al eliminar el estudiante.";
    header("Location: GestionarEstudiantes.php");
    exit();
}

$estudiantes = $model->obtenerEstudiantes();

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Gestión de Estudiantes</title>
  <link href="../assets/css/styles.css" rel="stylesheet" />
  <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  <script src="../assets/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
  <?php include('header.php'); ?>
  <div id="layoutSidenav">
    <?php include('sidebar.php'); ?>
    <div id="layoutSidenav_content">
      <main class="container-fluid px-4">
        <h1 class="mt-4">Gestión de Estudiantes</h1>
        <ol class="breadcrumb mb-4">
          <li class="breadcrumb-item active">Estudiantes</li>
        </ol>

        <a href="RegistrarEstudiante.php" class="btn btn-primary mb-3">
          <i class="fas fa-user-plus"></i> Registrar nuevo estudiante
        </a>

        <?php if ($mensaje): ?>
          <script>
            Swal.fire({
              icon: <?= preg_match('/exitosamente|registrado/i', $mensaje) ? "'success'" : "'error'" ?>,
              title: <?= preg_match('/exitosamente|registrado/i', $mensaje) ? "'¡Éxito!'" : "'Error'" ?>,
              text: <?= json_encode($mensaje) ?>,
              timer: 2500,
              showConfirmButton: false
            });
          </script>
        <?php endif; ?>

        <div class="card mb-4">
          <div class="card-header"><i class="fas fa-table me-1"></i> Estudiantes Registrados</div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="tablaEstudiantes" class="table table-bordered">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Edad</th>
                    <th>Dirección</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($estudiantes as $i => $e): ?>
                  <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($e['numero_matricula']) ?></td>
                    <td><?= htmlspecialchars($e['nombre'].' '.$e['apellido']) ?></td>
                    <td><?= htmlspecialchars($e['DNI']) ?></td>
                    <td><?= htmlspecialchars($e['edad']) ?></td>
                    <td><?= htmlspecialchars($e['direccion']) ?></td>
                    <td><?= htmlspecialchars($e['celular']) ?></td>
                    <td><?= htmlspecialchars($e['email']) ?></td>
                    <td>
                      <a href="ModificarEstudiante.php?id=<?= $e['id_estudiante'] ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $e['id_estudiante'] ?>)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../assets/js/jquery-3.5.1.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/jquery.dataTables.min.js"></script>
  <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#tablaEstudiantes').DataTable({
        language: {
          search: "🔍 Buscar:",
          lengthMenu: "Mostrar _MENU_ registros",
          zeroRecords: "No hay resultados",
          info: "Página _PAGE_ de _PAGES_",
          infoEmpty: "Sin registros",
          infoFiltered: "(de _MAX_ totales)",
          paginate: {
            first: "Primero", last: "Último",
            next: "Siguiente", previous: "Anterior"
          }
        }
      });
    });

    function confirmarEliminacion(id){
      Swal.fire({
        title: '¿Eliminar estudiante?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `GestionarEstudiantes.php?eliminar=${id}`;
        }
      });
    }
  </script>
</body>
</html>
