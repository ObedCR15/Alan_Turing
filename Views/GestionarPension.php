<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarPensionController.php');

// Crear controlador
$controller = new GestionarPensionController($pdo);

// Obtener parámetros de estudiante y programa
$id_estudiante = $_GET['id_estudiante'] ?? null;
$id_programa = $_GET['id_programa'] ?? null;

// Obtener la cantidad de cuotas para este programa
$cantidad_cuotas = $controller->obtenerCantidadCuotas($id_programa);

// Obtener las pensiones para un programa específico
$pensiones = $controller->obtenerPensionesPorPrograma($id_programa);

// Obtener el monto total de la pensión (esto lo puedes obtener de la tabla programas)
$stmt = $pdo->prepare("SELECT costo FROM programas WHERE id_programa = :id_programa");
$stmt->execute(['id_programa' => $id_programa]);
$programa = $stmt->fetch(PDO::FETCH_ASSOC);
$monto_pension = isset($programa['costo']) ? $programa['costo'] : 0;

// Crear las pensiones si no existen
if (count($pensiones) == 0) {
    // Crear pensiones con el monto dividido entre las cuotas
    $controller->crearPensionesSiNoExisten($id_estudiante, $id_programa, $cantidad_cuotas, $monto_pension);
    // Volver a obtener las pensiones después de crearlas
    $pensiones = $controller->obtenerPensionesPorPrograma($id_programa);
}

// Actualizar el estado de una pensión
if (isset($_GET['id_estudiante'], $_GET['id_programa'], $_GET['numero_cuota'], $_GET['estado_pension'])) {
    $id_estudiante = $_GET['id_estudiante'];
    $id_programa = $_GET['id_programa'];
    $numero_cuota = $_GET['numero_cuota'];
    $estado_pension = $_GET['estado_pension'];

    // Actualizar estado de la pensión
    $resultado = $controller->actualizarEstadoPension($id_estudiante, $id_programa, $numero_cuota, $estado_pension);

    // No se muestra el mensaje de éxito para el cambio de estado de la pensión
    header("Location: GestionarPension.php?id_estudiante=$id_estudiante&id_programa=$id_programa");
    exit();
}

// Guardar cambios (será activado cuando el formulario sea enviado)
if (isset($_POST['guardar_cambios'])) {
    $controller->guardarCambios($id_estudiante, $id_programa);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestionar Pensiones</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../assets/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
    <?php include('header.php'); ?>
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4">Gestionar Pensiones</h1>

                <!-- Notificaciones -->
                <?php if (isset($_SESSION['mensaje']) && !empty($_SESSION['mensaje']) && !isset($_GET['estado_pension'])): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: '<?= $_SESSION['mensaje'] ?>',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                    <?php unset($_SESSION['mensaje']); ?>  <!-- Limpiar el mensaje después de mostrarlo -->
                <?php endif; ?>

                <!-- Información de Cuotas -->
                <div class="alert alert-info">
                    <strong>Información del Programa:</strong> Este programa tiene un total de <strong><?= $cantidad_cuotas ?> cuotas</strong>.
                </div>

                <!-- Tabla de Pensiones -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i> Pensiones del Estudiante
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaPensiones" class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Cuota</th>
                                        <th>Estado de Pensión</th>
                                        <th>Monto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // Mostrar todas las cuotas, generadas en base a la cantidad de cuotas
                                    for ($i = 1; $i <= $cantidad_cuotas; $i++): 
                                        // Buscar la pensión correspondiente a esta cuota
                                        $pension = null;
                                        foreach ($pensiones as $p) {
                                            if ($p['numero_cuota'] == $i) {
                                                $pension = $p;
                                                break;
                                            }
                                        }
                                        // Si no se encuentra la pensión, se puede generar una "pendiente" con el monto distribuido
                                        $estado_pension = isset($pension) ? $pension['estado_pension'] : 'Pendiente';
                                        $monto_cuota = isset($pension) ? $pension['monto_pension'] : ($monto_pension / $cantidad_cuotas); // Distribuir monto
                                    ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td>Cuota <?= $i ?></td>
                                        <td>
                                            <span class="badge badge-<?= $estado_pension === 'Pagado' ? 'success' : 'warning' ?>">
                                                <?= $estado_pension ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            S/ <?= number_format($monto_cuota, 2) ?>
                                        </td>
                                        <td>
                                            <a href="GestionarPension.php?id_estudiante=<?= $id_estudiante ?>&id_programa=<?= $id_programa ?>&numero_cuota=<?= $i ?>&estado_pension=Pagado" 
                                               class="btn btn-sm btn-success">
                                               Marcar como Pagado
                                            </a>
                                            <a href="GestionarPension.php?id_estudiante=<?= $id_estudiante ?>&id_programa=<?= $id_programa ?>&numero_cuota=<?= $i ?>&estado_pension=Pendiente" 
                                               class="btn btn-sm btn-warning">
                                               Marcar como Pendiente
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Formulario para guardar cambios -->
                <form method="POST">
                    <!-- Botones para guardar o cancelar -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="GestionarPagos.php" class="btn btn-secondary">
                            <i class="fas fa-times-circle me-2"></i>Cancelar
                        </a>
                        <button type="submit" name="guardar_cambios" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>

            </main>
        </div>
    </div>

    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
