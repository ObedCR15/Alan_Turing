<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/GestionarUsuariosController.php');

$controller = new GestionarUsuariosController($pdo);

// Manejar operaciones GET para obtener datos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['obtener'])) {
    $id = filter_input(INPUT_GET, 'obtener', FILTER_VALIDATE_INT);
    if ($id) {
        header('Content-Type: application/json');
        echo json_encode($controller->obtenerAdministradorPorId($id));
        exit();
    }
}

// Manejar operaciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    try {
        switch($action) {
            case 'insertar':
                $controller->agregarAdministrador($_POST);
                $_SESSION['mensaje'] = "✅ Administrador creado exitosamente";
                break;
                
            case 'actualizar':
                $controller->actualizarAdministrador($id, $_POST);
                $_SESSION['mensaje'] = "✅ Administrador actualizado correctamente";
                break;
                
            case 'eliminar':
                $controller->eliminarUsuario($id);
                $_SESSION['mensaje'] = "✅ Administrador eliminado exitosamente";
                break;
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "❌ Error: " . $e->getMessage();
    }
    
    header('Location: GestionarUsuarios.php');
    exit();
}

// Obtener datos para la vista
$administradores = $controller->obtenerAdministradores();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Administradores</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="sb-nav-fixed">
    <?php include('header.php'); ?>
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4">
                <h1 class="mt-4">Gestión de Administradores</h1>

                <!-- Botón para nuevo administrador -->
                <button class="btn btn-success mb-3" onclick="mostrarModalCrear()">
                    <i class="fas fa-plus-circle me-1"></i> Nuevo Administrador
                </button>

                <!-- Mostrar mensajes -->
                <?php if ($mensaje): ?>
                    <script>
                        Swal.fire({
                            icon: '<?= strpos($mensaje, '✅') !== false ? 'success' : 'error' ?>',
                            title: '<?= $mensaje ?>',
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>

                <!-- Tabla de administradores -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>DNI</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($administradores as $admin): ?>
                                        <tr>
                                            <td><?= $admin['id_administrador'] ?></td>
                                            <td><?= htmlspecialchars($admin['nombre']) ?></td>
                                            <td><?= htmlspecialchars($admin['apellido']) ?></td>
                                            <td><?= htmlspecialchars($admin['email']) ?></td>
                                            <td><?= htmlspecialchars($admin['DNI']) ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-action me-1" 
                                                        onclick="mostrarModalEditar(<?= $admin['id_administrador'] ?>)">
                                                    <i class="fas fa-edit fa-lg"></i>
                                                </button>
                                                <button class="btn btn-danger btn-action" 
                                                        onclick="confirmarEliminacion(<?= $admin['id_administrador'] ?>)">
                                                    <i class="fas fa-trash-alt fa-lg"></i>
                                                </button>
                                            </td>
                                          
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal del formulario -->
                <div class="modal fade" id="modalFormulario" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" id="formAdmin">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitulo"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" id="formAction">
                                    <input type="hidden" name="id" id="formId">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nombre *</label>
                                        <input type="text" class="form-control" name="nombre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Apellido *</label>
                                        <input type="text" class="form-control" name="apellido" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">DNI (8 dígitos) *</label>
                                        <input type="text" class="form-control" name="DNI" pattern="\d{8}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Celular</label>
                                        <input type="text" class="form-control" name="celular">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control" name="direccion">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">* La Clave es igual al DNI</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
    // Función para mostrar modal de creación
    function mostrarModalCrear() {
        document.getElementById('formAction').value = 'insertar';
        document.getElementById('modalTitulo').textContent = 'Nuevo Administrador';
        document.getElementById('formId').value = '';
        document.getElementById('formAdmin').reset();
        new bootstrap.Modal(document.getElementById('modalFormulario')).show();
    }

    // Función para mostrar modal de edición
    async function mostrarModalEditar(id) {
        try {
            const response = await fetch(`GestionarUsuarios.php?obtener=${id}`);
            if (!response.ok) throw new Error('Error en la solicitud');
            
            const data = await response.json();
            
            document.getElementById('formAction').value = 'actualizar';
            document.getElementById('formId').value = id;
            document.getElementById('modalTitulo').textContent = 'Editar Administrador';
            
            const form = document.forms['formAdmin'];
            form.nombre.value = data.nombre;
            form.apellido.value = data.apellido;
            form.DNI.value = data.DNI;
            form.email.value = data.email;
            form.celular.value = data.celular || '';
            form.direccion.value = data.direccion || '';
            
            new bootstrap.Modal(document.getElementById('modalFormulario')).show();
        } catch (error) {
            Swal.fire('Error', error.message, 'error');
        }
    }

    // Función para confirmar eliminación
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Eliminar administrador?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'GestionarUsuarios.php';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'eliminar';
                form.appendChild(actionInput);
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
</body>
</html>