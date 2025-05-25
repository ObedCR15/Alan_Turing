<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarDocenteController.php');
require_once('../Controllers/EliminarDocenteController.php');

$controller = new GestionarDocenteController($pdo);
$eliminarController = new EliminarDocenteController($pdo);

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $_SESSION['mensaje'] = $eliminarController->eliminar($_GET['eliminar']);
    header("Location: GestionarDocente.php");
    exit();
}

// Obtener docentes
$docentes = $controller->mostrarDocentes();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Gestión de Docentes</title>
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
      <h1 class="mt-4">Gestión de Docentes</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Docentes</li>
      </ol>

      <a href="RegistrarDocente.php" class="btn btn-primary mb-3">
        <i class="fas fa-user-plus"></i> Registrar nuevo docente
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
            }).then(() => {
              if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
              }
            });
          });
        </script>
      <?php endif; ?>

      <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Docentes Registrados</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="tablaDocentes" class="table table-bordered">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Especialidad</th>
                  <th>DNI</th>
                  <th>Celular</th>
                  <th>Dirección</th>
                  <th>Edad</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($docentes as $i => $d): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= htmlspecialchars($d['nombre'] . ' ' . $d['apellido']) ?></td>
                  <td><?= htmlspecialchars($d['email']) ?></td>
                  <td><?= htmlspecialchars($d['especialidad']) ?></td>
                  <td><?= htmlspecialchars($d['DNI']) ?></td>
                  <td><?= htmlspecialchars($d['celular']) ?></td>
                  <td><?= htmlspecialchars($d['direccion']) ?></td>
                  <td><?= htmlspecialchars($d['edad']) ?></td>
                  <td>
                    <a href="ModificarDocente.php?id=<?= $d['id_docente'] ?>" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $d['id_docente'] ?>)">
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

<!-- Scripts -->
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function(){
    $('#tablaDocentes').DataTable({
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
      title: '¿Eliminar docente?',
      text: "Esta acción no se puede revertir",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `GestionarDocente.php?eliminar=${id}`;
      }
    });
  }
</script>
</body>
</html>
