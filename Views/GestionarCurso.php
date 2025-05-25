<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/EliminarCursoController.php');

// Instanciar el controlador
$controller = new EliminarCursoController($pdo);

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $mensaje = $controller->eliminarCurso($_GET['eliminar']);
    $_SESSION['mensaje'] = $mensaje;
    header("Location: GestionarCurso.php");
    exit();
}

// Obtener todos los cursos
$cursosStmt = $pdo->prepare("SELECT c.id_curso, c.nombre_curso, c.descripcion, 
                                    d.nombre AS nombre_docente, d.apellido AS apellido_docente
                             FROM cursos c
                             JOIN docentes d ON c.id_docente = d.id_docente");
$cursosStmt->execute();
$cursos = $cursosStmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar mensaje de sesión (si existe)
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Gestión de Cursos</title>
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
                <h1 class="mt-4">Gestión de Cursos</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Cursos</li>
                </ol>

                <a href="RegistrarCurso.php" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Registrar nuevo curso
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
                    <div class="card-header"><i class="fas fa-table me-1"></i> Cursos Registrados</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tablaCursos" class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre del Curso</th>
                                        <th>Descripción</th>
                                        <th>Docente Responsable</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cursos as $i => $curso): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                                            <td><?= htmlspecialchars($curso['descripcion']) ?></td>
                                            <td><?= htmlspecialchars($curso['nombre_docente'] . ' ' . $curso['apellido_docente']) ?></td>
                                            <td>
                                                <a href="ModificarCurso.php?id=<?= $curso['id_curso'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion(<?= $curso['id_curso'] ?>)">
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
            $('#tablaCursos').DataTable({
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
                title: '¿Eliminar curso?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?eliminar=' + id;
                }
            });
        }
    </script>
</body>
</html>
