<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarPagosController.php');

$controller = new GestionarPagosController($pdo);

$id_programa = $_GET['id_programa'] ?? null;
$pagos = $controller->obtenerPagosPorPrograma($id_programa);
$programas = $controller->obtenerProgramas();
$pensiones = $controller->obtenerPensionesPorPrograma($id_programa);
$ingresos_totales = $controller->obtenerIngresoTotal(); // Ingresos Totales (matrículas + pensiones)
$ingresos_pensiones = $controller->obtenerIngresosPensiones(); // Ingresos de pensiones
$ingresos_matriculas = $controller->obtenerIngresosMatriculas(); // Ingresos de matrículas

if (isset($_GET['id_estudiante'], $_GET['id_programa'], $_GET['numero_cuota'], $_GET['estado_pension'])) {
    $resultado = $controller->actualizarEstadoPension(
        $_GET['id_estudiante'],
        $_GET['id_programa'],
        $_GET['numero_cuota'],
        $_GET['estado_pension']
    );
    
    $_SESSION['mensaje'] = $resultado ? "✅ Pensión actualizada" : "❌ Error al actualizar";
    header('Location: GestionarPagos.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionar Pagos</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="../assets/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
    <?php include('header.php'); ?>
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4">Gestionar Pagos</h1>

                <div class="d-flex justify-content-between mb-4">
                    <!-- Sección de Ingresos Totales -->
                    <div class="alert alert-success" style="flex: 1; margin-right: 10px;">
                        <h4 class="alert-heading">Ingresos Totales</h4>
                        <h2>S/ <?= number_format($ingresos_totales, 2) ?></h2>
                    </div>

                    <!-- Sección de Ingresos de Pensiones -->
                    <div class="alert alert-info" style="flex: 1; margin-right: 10px;">
                        <h4 class="alert-heading">Ingresos de Pensiones</h4>
                        <h2>S/ <?= number_format($ingresos_pensiones, 2) ?></h2>
                    </div>

                    <!-- Sección de Ingresos de Matrículas -->
                    <div class="alert alert-warning" style="flex: 1;">
                        <h4 class="alert-heading">Ingresos de Matrículas</h4>
                        <h2>S/ <?= number_format($ingresos_matriculas, 2) ?></h2>
                    </div>
                </div>

                <!-- Filtro de Programas -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-filter me-2"></i>Filtrar por Programa
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-6">
                                <select name="id_programa" class="form-select">
                                    <option value="">Todos los programas</option>
                                    <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id_programa'] ?>" 
                                        <?= ($id_programa == $p['id_programa']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre_programa']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notificaciones -->
                <?php if (isset($_SESSION['mensaje'])): ?>
                <script>
                    Swal.fire({
                        icon: '<?= strpos($_SESSION['mensaje'], '✅') !== false ? 'success' : 'error' ?>',
                        title: '<?= strpos($_SESSION['mensaje'], '✅') !== false ? 'Éxito' : 'Error' ?>',
                        text: '<?= addslashes(str_replace(['✅','❌'], '', $_SESSION['mensaje'])) ?>',
                        timer: 3000
                    });
                </script>
                <?php unset($_SESSION['mensaje']); endif; ?>

                <!-- Tabla Principal -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-table me-2"></i>Detalle de Estudiantes Matriculados
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Estudiante</th>
                                        <th>DNI</th>
                                        <th>Estado Matrícula</th>
                                        <th>Monto Matrícula</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagos as $i => $pago): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($pago['nombre_estudiante'] . ' ' . $pago['apellido_estudiante']) ?></td>
                                        <td><?= htmlspecialchars($pago['dni_estudiante']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $pago['estado_matricula'] === 'Pagado' ? 'success' : 'warning' ?>">
                                                <?= $pago['estado_matricula'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end">S/ <?= number_format($pago['monto_matricula'] ?? 0, 2) ?></td>
                                        <td>
                                            <a href="GestionarPension.php?id_estudiante=<?= $pago['id_estudiante'] ?>&id_programa=<?= $pago['id_programa'] ?? 0 ?>" 
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-2"></i>Gestionar
                                            </a>
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

    <!-- Referencias de los scripts al final del body -->
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>
