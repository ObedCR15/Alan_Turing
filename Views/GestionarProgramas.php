<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarProgramasController.php');
require_once('../Controllers/EliminarProgramaController.php'); // Incluye el controlador de eliminación

// Crear instancia del controlador
$controller = new GestionarProgramasController($pdo);

// Obtener los programas
$programas = $controller->obtenerProgramas();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

// Comprobar si se ha solicitado eliminar un programa
if (isset($_GET['eliminar'])) {
    $id_programa = $_GET['eliminar'];
    $eliminarController = new EliminarProgramaController($pdo); // Instancia del controlador de eliminación
    $mensaje = $eliminarController->eliminar($id_programa) 
        ? 'Programa eliminado exitosamente.' 
        : 'Error al eliminar el programa.'; 
    
    $_SESSION['mensaje'] = $mensaje;
    header("Location: GestionarProgramas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Gestión de Programas</title>
  <link href="../assets/css/styles.css" rel="stylesheet" />
  <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
  <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
  <?php include('header.php'); ?>
  <div id="layoutSidenav">
    <?php include('sidebar.php'); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h1 class="mt-4">Gestión de Programas</h1>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Programas</li>
          </ol>

          <a href="RegistrarPrograma.php" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Registrar nuevo programa
          </a>

          <?php if ($mensaje): ?>
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                  icon: <?= stripos($mensaje, 'exitosamente') !== false ? "'success'" : "'error'" ?>,
                  title: <?= stripos($mensaje, 'exitosamente') !== false ? "'¡Éxito!'" : "'Error'" ?>,
                  text: <?= json_encode($mensaje) ?>,
                  timer: 2500,
                  showConfirmButton: false
                });
              });
            </script>
          <?php endif; ?>

          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-table me-1"></i> Programas Registrados</div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tablaProgramas" class="table table-bordered">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Matricula</th>
                      <th>Pensión</th>
                      <th>Pensiones</th>
                      <th>Duración</th>
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Cursos</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($programas as $i => $programa): ?>
                      <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($programa['nombre_programa']) ?></td>
                        <td><?= htmlspecialchars($programa['descripcion']) ?></td>
                        <td>S/ <?= number_format($programa['costo'], 2) ?></td>
                        <td>S/ <?= number_format($programa['pension'], 2) ?></td>
                        <td><?= $programa['pensiones'] ?></td>
                        <td><?= $programa['duracion'] ?> semanas</td>
                        <td><?= $programa['fecha_inicio'] ?></td>
                        <td><?= $programa['fecha_fin'] ?></td>
                        <td>
                          <?= !empty($programa['cursos']) ? nl2br(htmlspecialchars($programa['cursos'])) : 'Sin cursos' ?>
                        </td>
                        <td>
                          <a href="ModificarPrograma.php?id=<?= $programa['id_programa'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                          </a>
                          <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $programa['id_programa'] ?>)">
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

        </div>
      </main>
    </div>
  </div>

  <script src="../assets/js/jquery-3.5.1.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/jquery.dataTables.min.js"></script>
  <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#tablaProgramas').DataTable({
        language: {
          search: "🔍 Buscar programa:",
          lengthMenu: "Mostrar _MENU_ registros por página",
          zeroRecords: "No se encontraron programas",
          info: "Mostrando página _PAGE_ de _PAGES_",
          infoEmpty: "Sin registros",
          infoFiltered: "(de _MAX_ totales)",
          paginate: {
            first: "Primero", last: "Último",
            next: "Siguiente", previous: "Anterior"
          }
        }
      });
    });

    function confirmarEliminacion(id) {
      Swal.fire({
        title: '¿Eliminar programa?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "?eliminar=" + id;
        }
      });
    }
  </script>

</body>
</html>
