<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/RegistrarMatriculaController.php');

$controller = new RegistrarMatriculaController($pdo);

// Variables de control
$estudiantes = [];
$estudianteSeleccionado = null;
$nombre_busqueda = "";

// Procesar búsqueda de estudiante
if (isset($_GET['buscar_estudiante']) && !empty($_GET['nombre_apellido'])) {
    $nombre_busqueda = trim($_GET['nombre_apellido']);
    $estudiantes = $controller->buscarEstudiantePorNombreApellido($nombre_busqueda);
}

// Obtener estudiante seleccionado
if (isset($_GET['id_estudiante'])) {
    $id_estudiante = (int)$_GET['id_estudiante'];
    $estudianteSeleccionado = $controller->obtenerEstudiantePorId($id_estudiante);
}

// Obtener lista de programas
$programas = $controller->obtenerProgramas();

// Procesar formulario de matrícula
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_estudiante'], $_POST['id_programa'], $_POST['monto_matricula'], $_POST['estado_matricula'], $_POST['descuento'])) {
        
        $id_estudiante = (int)$_POST['id_estudiante'];
        $id_programa = (int)$_POST['id_programa'];
        $monto_matricula = (float)$_POST['monto_matricula'];
        $estado_matricula = $_POST['estado_matricula'];
        $descuento = (float)$_POST['descuento'];
        
        // Registrar matrícula
        $mensaje = $controller->registrarMatricula(
            $id_estudiante,
            $id_programa,
            $monto_matricula,
            $estado_matricula,
            $descuento
        );
        
        $_SESSION['mensaje'] = $mensaje;
        header("Location: GestionarMatricula.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Registrar Matrícula</title>
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
                <h1 class="mt-4">Registrar Matrícula</h1>

                <!-- Buscador de Estudiantes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-search me-1"></i> Buscar Estudiante
                    </div>
                    <div class="card-body">
                        <form method="GET" action="RegistrarMatricula.php">
                            <div class="input-group">
                                <input type="text" name="nombre_apellido" class="form-control" 
                                       placeholder="Ingrese nombre o apellido" 
                                       value="<?= htmlspecialchars($nombre_busqueda) ?>" required>
                                <button type="submit" name="buscar_estudiante" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Resultados de Búsqueda -->
                <?php if (!empty($estudiantes)): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-users me-1"></i> Estudiantes Encontrados
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>DNI</th>
                                        <th>Edad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $i => $est): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($est['nombre']) ?></td>
                                        <td><?= htmlspecialchars($est['apellido']) ?></td>
                                        <td><?= htmlspecialchars($est['DNI']) ?></td>
                                        <td><?= htmlspecialchars($est['edad']) ?> años</td>
                                        <td class="text-center">
                                            <a href="RegistrarMatricula.php?id_estudiante=<?= $est['id_estudiante'] ?>&nombre_apellido=<?= urlencode($nombre_busqueda) ?>" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-plus-circle me-1"></i> Seleccionar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Formulario de Matrícula -->
                <?php if (isset($estudianteSeleccionado)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-user-graduate me-1"></i> Datos del Estudiante
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Información del Estudiante</h5>
                                        <p class="card-text">
                                            <strong>Nombre:</strong> <?= htmlspecialchars($estudianteSeleccionado['nombre']) ?><br>
                                            <strong>Apellido:</strong> <?= htmlspecialchars($estudianteSeleccionado['apellido']) ?><br>
                                            <strong>DNI:</strong> <?= htmlspecialchars($estudianteSeleccionado['DNI']) ?><br>
                                            <strong>Edad:</strong> <?= htmlspecialchars($estudianteSeleccionado['edad']) ?> años
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Código de Matrícula</h5>
                                        <h2 class="text-success"><?= htmlspecialchars($estudianteSeleccionado['numero_matricula']) ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="RegistrarMatricula.php">
                            <input type="hidden" name="id_estudiante" value="<?= $estudianteSeleccionado['id_estudiante'] ?>">

                            <!-- Selección de Programa -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-info text-white">
                                            <i class="fas fa-book me-1"></i> Programa Académico
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Seleccione Programa:</label>
                                                <select name="id_programa" id="id_programa" class="form-select" required onchange="actualizarCostos()">
                                                    <option value="">-- Seleccione un programa --</option>
                                                    <?php foreach ($programas as $programa): ?>
                                                    <option value="<?= $programa['id_programa'] ?>" 
                                                            data-costo="<?= $programa['costo'] ?>" 
                                                            data-pension="<?= $programa['pension'] ?>"
                                                            data-duracion="<?= $programa['duracion'] ?> (semanas)"
                                                            data-pensiones="<?= $programa['pensiones'] ?? 0 ?>">
                                                        <?= htmlspecialchars($programa['nombre_programa']) ?> 
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Costo Total:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">S/</span>
                                                            <input type="text" id="costo_programa" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Pensión Original:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">S/</span>
                                                            <input type="text" id="pension_programa" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Pensión con Descuento:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">S/</span>
                                                            <input type="text" id="pension_con_descuento" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Duración:</label>
                                                        <input type="text" id="duracion_programa" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label">N° de Pensiones:</label>
                                                        <input type="text" id="pensiones_programa" class="form-control" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos de Pago -->
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header bg-warning text-dark">
                                            <i class="fas fa-money-bill-wave me-1"></i> Información de Pago
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Estado de Pago:</label>
                                                <select name="estado_matricula" id="estado_matricula" class="form-select" required onchange="actualizarMonto()">
                                                    <option value="Pendiente">Pendiente</option>
                                                    <option value="Pagado">Pagado</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label">Monto a Pagar:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">S/</span>
                                                    <input type="number" name="monto_matricula" id="monto_matricula" 
                                                           class="form-control" step="0.01" min="0" required>
                                                </div>
                                                <small class="text-muted">* Monto automático si está Pagado</small>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">Descuento en %:</label>
                                                <input type="number" name="descuento" id="descuento" 
                                                       class="form-control" step="0.01" min="0" max="100" value="0.00">
                                                <small class="text-muted">* Ingrese el porcentaje de descuento</small>
                                            </div>

                                            <button type="button" class="btn btn-primary mb-3" onclick="generarDescuento()">
                                                <i class="fas fa-percentage me-1"></i> Aplicar Descuento
                                            </button>

                                            <div class="form-group mb-3">
                                                <label class="form-label">Descuento Aplicado:</label>
                                                <input type="text" id="descuento_aplicado" class="form-control" readonly>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="form-label">Monto Final a Pagar:</label>
                                                <input type="text" id="monto_final" class="form-control" readonly>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="fas fa-save me-1"></i> Registrar Matrícula
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
    function actualizarCostos() {
        const programa = document.getElementById('id_programa');
        const opcion = programa.options[programa.selectedIndex];
        
        const costo = opcion.getAttribute('data-costo') || 0;
        const pension = opcion.getAttribute('data-pension') || 0;
        const duracion = opcion.getAttribute('data-duracion') || 0;
        const pensiones = opcion.getAttribute('data-pensiones') || 0;

        document.getElementById('costo_programa').value = parseFloat(costo).toFixed(2);
        document.getElementById('pension_programa').value = parseFloat(pension).toFixed(2);
        document.getElementById('duracion_programa').value = duracion;
        document.getElementById('pensiones_programa').value = pensiones;

        // Calcular pensión con descuento inicial
        generarDescuento();
        actualizarMonto();
    }

    function calcularCostoConDescuento() {
        const costoOriginal = parseFloat(document.getElementById('costo_programa').value) || 0;
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;
        return costoOriginal - (costoOriginal * descuento / 100);
    }

    function actualizarMonto() {
        const estado = document.getElementById('estado_matricula').value;
        const montoInput = document.getElementById('monto_matricula');
        const costoConDescuento = calcularCostoConDescuento();
        
        if (estado === 'Pagado') {
            montoInput.value = costoConDescuento.toFixed(2);
            montoInput.readOnly = true;
            montoInput.min = 0;
            montoInput.max = '';
            montoInput.placeholder = 'Monto automático';
        } else {
            montoInput.value = '';
            montoInput.readOnly = false;
            montoInput.min = 1;
            montoInput.max = costoConDescuento;
            montoInput.placeholder = `Máximo: S/${costoConDescuento.toFixed(2)}`;
        }
        generarDescuento();
    }

    function generarDescuento() {
        const costoConDescuento = calcularCostoConDescuento();
        const estado = document.getElementById('estado_matricula').value;
        const montoInput = document.getElementById('monto_matricula');
        
        // Calcular y mostrar pensión con descuento
        const pensionOriginal = parseFloat(document.getElementById('pension_programa').value) || 0;
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;
        const pensionConDescuento = pensionOriginal - (pensionOriginal * descuento / 100);
        document.getElementById('pension_con_descuento').value = pensionConDescuento.toFixed(2);

        // Actualizar máximo en tiempo real
        if (estado === 'Pendiente') {
            montoInput.max = costoConDescuento;
            montoInput.placeholder = `Máximo: S/${costoConDescuento.toFixed(2)}`;
        }

        document.getElementById('descuento_aplicado').value = (costoConDescuento - parseFloat(document.getElementById('costo_programa').value)).toFixed(2);
        document.getElementById('monto_final').value = estado === 'Pagado' 
            ? costoConDescuento.toFixed(2) 
            : (parseFloat(montoInput.value) || 0).toFixed(2);
    }

    // Validación al enviar formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const estado = document.getElementById('estado_matricula').value;
        const monto = parseFloat(document.getElementById('monto_matricula').value);
        const costoConDescuento = calcularCostoConDescuento();

        if (estado === 'Pendiente' && (monto < 1 || monto > costoConDescuento)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Monto inválido',
                text: `Cuando el estado es Pendiente, el monto debe estar entre 1 y ${costoConDescuento.toFixed(2)}`
            });
        }
    });

    // Eventos para actualizar en tiempo real
    document.getElementById('descuento').addEventListener('input', function() {
        actualizarMonto();
        generarDescuento();
    });

    document.addEventListener('DOMContentLoaded', actualizarCostos);
</script>

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
</body>
</html>