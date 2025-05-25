<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarMatriculaController.php');
require_once('../Controllers/EliminarMatriculaController.php'); // Asegúrate de incluir el controlador

$controller = new GestionarMatriculaController($pdo);
$eliminarController = new EliminarMatriculaController($pdo); // Instanciar el controlador para eliminar matrícula

// Filtrar por programa
$id_programa = $_GET['id_programa'] ?? null;
$matriculas = $controller->mostrarMatriculas($id_programa);

// Obtener todos los programas
$programas = $controller->mostrarProgramas();

// Procesar eliminación
if (isset($_GET['eliminar_estudiante']) && isset($_GET['eliminar_programa'])) {
    $id_estudiante = $_GET['eliminar_estudiante'];
    $id_programa = $_GET['eliminar_programa'];

    // Llamar al método de eliminar matrícula
    $resultado = $eliminarController->eliminarMatricula($id_estudiante, $id_programa);
    
    // Mensaje dependiendo del resultado
    if ($resultado) {
        $_SESSION['mensaje'] = "✅ Matrícula eliminada correctamente";
    } else {
        $_SESSION['mensaje'] = "❌ Error al eliminar la matrícula";
    }

    // Redirigir de nuevo a la misma página para mostrar el mensaje
    header("Location: GestionarMatricula.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestión de Matrículas</title>
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
                <h1 class="mt-4">Gestión de Matrículas</h1>

                <!-- Mostrar el mensaje de error o éxito con SweetAlert -->
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <script>
                        const mensaje = '<?= $_SESSION['mensaje']; ?>';
                        const icono = mensaje.includes('Error') ? 'error' : 'success';  // Cambiar el ícono según el mensaje
                        const titulo = mensaje.includes('Error') ? '¡Error!' : '¡Éxito!';  // Cambiar título según el mensaje
                        Swal.fire({
                            icon: icono,
                            title: titulo,
                            text: mensaje,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                    <?php unset($_SESSION['mensaje']); ?>  <!-- Limpiar el mensaje después de mostrarlo -->
                <?php endif; ?>

                <!-- Filtro por programa -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <form method="GET" class="form-inline">
                            <div class="form-group mr-3">
                                <select name="id_programa" class="form-control">
                                    <option value="">Todos los programas</option>
                                    <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id_programa'] ?>" 
                                        <?= $id_programa == $p['id_programa'] ? 'selected' : '' ?> >
                                        <?= htmlspecialchars($p['nombre_programa']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mr-3">Filtrar</button>
                        </form>
                    </div>
                </div>

                <!-- Botón para añadir nueva matrícula -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <a href="RegistrarMatricula.php" class="btn btn-success">Añadir Nueva Matrícula</a>
                    </div>
                </div>

                <!-- Tabla de Matrículas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Registros de Matrículas
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Verifica si hay matrículas disponibles -->
                            <?php if (!empty($matriculas)): ?>
                                <table id="tablaMatriculas" class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Código de Matrícula</th>
                                            <th>Estudiante</th>
                                            <th>DNI</th>
                                            <th>Programa</th>
                                            <th>Estado Matrícula</th>
                                            <th>Monto Matrícula</th>
                                            <th>Monto Pensión</th>
                                            <th>Descuento</th>
                                            <th>Duración (Meses)</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($matriculas as $i => $m): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($m['numero_matricula']) ?></td>
                                            <td><?= htmlspecialchars($m['nombre_estudiante'] . ' ' . $m['apellido_estudiante']) ?></td>
                                            <td><?= htmlspecialchars($m['dni_estudiante'] ?? 'No disponible') ?></td>
                                            <td><?= htmlspecialchars($m['nombre_programa'] ?? 'No disponible') ?></td>
                                            <td>
                                                <span class="badge badge-<?= isset($m['estado_matricula']) && $m['estado_matricula'] === 'Pagado' ? 'success' : 'warning' ?>">
                                                    <?= $m['estado_matricula'] ?? 'No disponible' ?>
                                                </span>
                                            </td>
                                            <td class="text-right">S/ <?= number_format($m['monto_matricula'] ?? 0, 2) ?></td>
                                            <td class="text-right">
                                                <span class="text-success">S/ <?= number_format($m['pension_con_descuento'] ?? 0, 2) ?></span>
                                                <br>
                                                <small class="text-muted text-decoration-line-through">
                                                    S/ <?= number_format($m['monto_pension'] ?? 0, 2) ?>
                                                </small>
                                            </td>
                                            <td class="text-right"><?= number_format($m['descuento'] ?? 0) ?> %</td>
                                            <td class="text-right"><?= htmlspecialchars($m['duracion']) ?> meses</td>
                                            <td>
                                                <a href="ModificarMatricula.php?id_estudiante=<?= $m['id_estudiante'] ?>&id_programa=<?= $m['id_programa'] ?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" 
                                                        title="Eliminar"
                                                        onclick="confirmarEliminacion(<?= $m['id_estudiante'] ?>, <?= $m['id_programa'] ?>)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No se encontraron matrículas para el programa seleccionado.</p>
                            <?php endif; ?>
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
        $(document).ready(function() {
            $('#tablaMatriculas').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                columnDefs: [
                    { orderable: false, targets: [10] } // Deshabilitar orden en columna de acciones
                ]
            });
        });

        function confirmarEliminacion(est, prog) {
            Swal.fire({
                title: '¿Confirmar eliminación?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `GestionarMatricula.php?eliminar_estudiante=${est}&eliminar_programa=${prog}`;
                }
            });
        }
    </script>
</body>
</html>
