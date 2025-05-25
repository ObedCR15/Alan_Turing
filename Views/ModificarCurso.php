<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/ModificarCursoController.php');

$controller = new ModificarCursoController($pdo);

// Verificar si se ha enviado el formulario de modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_curso'];
    $nombre = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $id_docente = $_POST['id_docente'];

    $mensaje = $controller->modificarCurso($id, $nombre, $descripcion, $id_docente) 
        ? "Curso modificado exitosamente." 
        : "Error al modificar el curso.";

    $_SESSION['mensaje'] = $mensaje;
    header("Location: GestionarCurso.php");
    exit();
}

// Obtener el ID del curso que se desea modificar
$id = $_GET['id'];
$curso = $controller->obtenerCursoPorId($id);
if (!$curso) {
    $_SESSION['mensaje'] = "Curso no encontrado.";
    header("Location: GestionarCurso.php");
    exit();
}

// Obtener los docentes disponibles para la selección
$docentes = $controller->obtenerDocentes();

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modificar Curso</title>
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
            <h1 class="mt-4">Modificar Curso</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="GestionarCurso.php">Cursos</a></li>
                <li class="breadcrumb-item active">Modificar</li>
            </ol>

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
                <div class="card-body">
                    <form action="ModificarCurso.php" method="POST">
                        <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>" />
                        <div class="mb-3">
                            <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                            <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" value="<?= htmlspecialchars($curso['nombre_curso']) ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required><?= htmlspecialchars($curso['descripcion']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="docente" class="form-label">Docente Responsable</label>
                            <select class="form-control" id="id_docente" name="id_docente" required>
                                <?php foreach ($docentes as $docente): ?>
                                    <option value="<?= $docente['id_docente'] ?>" <?= $docente['id_docente'] == $curso['id_docente'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido'] . ' - Especialidad: ' . $docente['especialidad']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Modificar Curso</button>
                        <a href="GestionarCurso.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sweetalert2.min.js"></script>
</body>
</html>
