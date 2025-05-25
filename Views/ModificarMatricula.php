<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/ModificarMatriculaController.php');

$controller = new ModificarMatriculaController($pdo);
$estudiante = null;
$programaActual = null;

if (isset($_GET['id_estudiante'])) {
    $id_estudiante = (int)$_GET['id_estudiante'];
    $estudiante = $controller->obtenerEstudianteConMatricula($id_estudiante);
    $programaActual = $estudiante ? $controller->obtenerProgramaDetalles($estudiante['id_programa']) : null;
}

$programas = $controller->obtenerProgramas();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $estudiante) {
    $resultado = $controller->procesarActualizacion($_POST, $estudiante['id_estudiante']);
    $_SESSION['mensaje'] = $resultado ? "✅ Matrícula actualizada correctamente" : "❌ Error en la actualización";
    header("Location: GestionarMatricula.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Matrícula</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="sb-nav-fixed">
    <?php include('header.php'); ?>
    
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Modificar Matrícula</h1>
                </div>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <script>
                        Swal.fire({
                            icon: '<?= strpos($_SESSION['mensaje'], 'Error') !== false ? 'error' : 'success' ?>',
                            title: '<?= strpos($_SESSION['mensaje'], 'Error') !== false ? '¡Error!' : '¡Éxito!' ?>',
                            text: '<?= $_SESSION['mensaje'] ?>',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    </script>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>

                <?php if ($estudiante && $programaActual): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Editar Matrícula de <?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?>
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id_programa_actual" value="<?= $estudiante['id_programa'] ?>">

                            <!-- Sección Información Actual -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Información del Estudiante</label>
                                        <div class="form-control-plaintext bg-white p-3 rounded border">
                                            <div class="mb-2">
                                                <span class="text-muted">Código:</span>
                                                <?= htmlspecialchars($estudiante['numero_matricula']) ?>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">Programa Actual:</span>
                                                <?= htmlspecialchars($programaActual['nombre_programa']) ?>
                                            </div>
                                            <div>
                                                <span class="text-muted">Monto Actual:</span>
                                                S/ <?= number_format($estudiante['monto_matricula'], 2) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Configuración de Matrícula</label>
                                        <div class="bg-white p-3 rounded border">
                                            <div class="mb-3">
                                                <label class="form-label">Nuevo Programa:</label>
                                                <select name="id_programa" class="form-select" required onchange="actualizarCostos()">
                                                    <?php foreach ($programas as $p): ?>
                                                    <option value="<?= $p['id_programa'] ?>" 
                                                        <?= ($p['id_programa'] == $estudiante['id_programa']) ? 'selected' : '' ?>
                                                        data-costo="<?= $p['costo'] ?>"
                                                        data-pension="<?= $p['pension'] ?>">
                                                        <?= htmlspecialchars($p['nombre_programa']) ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Estado:</label>
                                                <select name="estado_matricula" class="form-select" id="estado_matricula" onchange="toggleMontoInput()">
                                                    <option value="Pendiente" <?= ($estudiante['estado_matricula'] == 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
                                                    <option value="Pagado" <?= ($estudiante['estado_matricula'] == 'Pagado') ? 'selected' : '' ?>>Pagado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección Descuentos y Pagos -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Configuración de Descuentos</label>
                                        <div class="bg-white p-3 rounded border">
                                            <div class="mb-3">
                                                <label class="form-label">Tiene Beca:</label>
                                                <select name="beca" class="form-select" id="beca" onchange="toggleDescuento()">
                                                    <option value="0" <?= ($estudiante['descuento'] == 0) ? 'selected' : '' ?>>No</option>
                                                    <option value="1" <?= ($estudiante['descuento'] > 0) ? 'selected' : '' ?>>Si</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Porcentaje de Descuento:</label>
                                                <div class="input-group">
                                                    <input type="number" name="descuento" id="descuento" 
                                                        class="form-control" 
                                                        value="<?= $estudiante['descuento'] ?>" 
                                                        min="0" max="100" 
                                                        <?= ($estudiante['descuento'] == 0) ? 'disabled' : '' ?>
                                                        oninput="validarDescuento(); calcularMontoFinal()">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Detalles de Pago</label>
                                        <div class="bg-white p-3 rounded border">
                                            <div class="mb-3">
                                                <label class="form-label">Costo del Programa:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">S/</span>
                                                    <input type="text" id="costo_programa" class="form-control" 
                                                        value="<?= $programaActual['costo'] ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Monto a Pagar:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">S/</span>
                                                    <input type="number" name="monto_matricula" id="monto_matricula" 
                                                        class="form-control <?= ($estudiante['estado_matricula'] == 'Pagado') ? 'bg-light' : '' ?>" 
                                                        value="<?= $estudiante['monto_matricula'] ?>" 
                                                        step="0.01" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Nueva Pensión Mensual:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">S/</span>
                                                    <input type="text" id="pension_final" class="form-control" 
                                                        value="<?= number_format(($programaActual['pension'] * (1 - ($estudiante['descuento'] / 100))), 2) ?>" 
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="GestionarMatricula.php" class="btn btn-secondary">
                                    <i class="fas fa-times-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    // Funciones JavaScript aquí
                    function actualizarCostos() {
                        const programa = document.querySelector('select[name="id_programa"]');
                        const selected = programa.options[programa.selectedIndex];
                        document.getElementById('costo_programa').value = selected.dataset.costo;
                        calcularMontoFinal();
                    }

                    function toggleDescuento() {
                        const beca = document.getElementById('beca');
                        const descuentoInput = document.getElementById('descuento');
                        
                        descuentoInput.disabled = beca.value === '0';
                        if (beca.value === '0') descuentoInput.value = 0;
                        calcularMontoFinal();
                    }

                    function validarDescuento() {
                        const descuento = document.getElementById('descuento');
                        if (descuento.value > 100) {
                            Swal.fire('Error', 'El descuento no puede ser mayor a 100%', 'error');
                            descuento.value = 100;
                        }
                        if (descuento.value < 0) {
                            Swal.fire('Error', 'El descuento no puede ser negativo', 'error');
                            descuento.value = 0;
                        }
                    }

                    function calcularMontoFinal() {
                        const costo = parseFloat(document.getElementById('costo_programa').value);
                        const descuento = parseFloat(document.getElementById('descuento').value) || 0;
                        const montoInput = document.getElementById('monto_matricula');
                        const pension = parseFloat(document.querySelector('select[name="id_programa"]').selectedOptions[0].dataset.pension);
                        
                        const montoFinal = costo * (1 - (descuento / 100));
                        montoInput.value = montoFinal.toFixed(2);
                        
                        document.getElementById('pension_final').value = (pension * (1 - (descuento / 100))).toFixed(2);
                    }

                    function toggleMontoInput() {
                        const estado = document.getElementById('estado_matricula').value;
                        const montoInput = document.getElementById('monto_matricula');
                        
                        if (estado === 'Pagado') {
                            montoInput.readOnly = true;
                            montoInput.classList.add('bg-light');
                            calcularMontoFinal();
                        } else {
                            montoInput.readOnly = false;
                            montoInput.classList.remove('bg-light');
                        }
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        toggleDescuento();
                        toggleMontoInput();
                        calcularMontoFinal();
                    });
                </script>

                <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    No se encontró el estudiante solicitado.
                </div>
                <?php endif; ?>
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
