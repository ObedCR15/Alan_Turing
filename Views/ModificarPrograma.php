<?php
// ModificarPrograma.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/ModificarProgramaController.php');
require_once('../Models/ModificarProgramaModel.php');

// Crear instancia del controlador
$model = new ModificarProgramaModel($pdo);
$controller = new ModificarProgramaController($model);

// Obtener el ID del programa que se quiere modificar
$programa_id = $_GET['id'];  // Esto depende de cómo estás pasando el ID, por ejemplo, a través de GET

// Obtener los cursos asociados a este programa
$programa_cursos = $controller->getCursosAsociados($programa_id);

// Obtener todos los cursos disponibles
$cursos = $controller->getCursos();

// Obtener los detalles del programa
$sql = "SELECT * FROM programas WHERE id_programa = :id_programa";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_programa', $programa_id);
$stmt->execute();
$programa = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_programa = $_POST['nombre_programa'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $pension = $_POST['pension'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $duracion = $_POST['duracion'];
    $pensiones = $_POST['pensiones'];
    $cursos_seleccionados = $_POST['curso'];

    // Actualizar el programa
    $controller->updatePrograma($programa_id, $nombre_programa, $descripcion, $costo, $pension, $fecha_inicio, $fecha_fin, $duracion, $pensiones, $cursos_seleccionados);

    $_SESSION['mensaje'] = '¡Programa modificado exitosamente!';
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
    <title>Modificar Programa</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <script>
        function calcularDuracion() {
            var fecha_inicio = new Date(document.getElementById('fecha_inicio').value);
            var fecha_fin = new Date(document.getElementById('fecha_fin').value);
            if (fecha_inicio && fecha_fin) {
                var diffTime = Math.abs(fecha_fin - fecha_inicio);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convertir la diferencia en días
                var duracion = Math.ceil(diffDays / 7); // Convertir los días en semanas
                document.getElementById('duracion').value = duracion;
                // Calcular la cantidad de pensiones (por ejemplo, 4 pensiones por duración)
                document.getElementById('pensiones').value = Math.ceil(duracion / 4);
            }
        }
    </script>
</head>
<body>
    <?php include('header.php'); ?>
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Modificar Programa</h1>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-info">
                            <?php echo $_SESSION['mensaje']; ?>
                            <?php unset($_SESSION['mensaje']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="ModificarPrograma.php?id=<?php echo $programa_id; ?>" method="POST">
                        <div class="form-group">
                            <label for="nombre_programa">Nombre del Programa</label>
                            <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" value="<?php echo $programa['nombre_programa']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $programa['descripcion']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="costo">Costo de matrícula</label>
                            <input type="number" class="form-control" id="costo" name="costo" value="<?php echo $programa['costo']; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="pension">Pensión del Programa</label>
                            <input type="number" class="form-control" id="pension" name="pension" value="<?php echo $programa['pension']; ?>" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $programa['fecha_inicio']; ?>" required onchange="calcularDuracion()">
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $programa['fecha_fin']; ?>" required onchange="calcularDuracion()">
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración (semanas)</label>
                            <input type="number" class="form-control" id="duracion" name="duracion" value="<?php echo $programa['duracion']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="pensiones">Cantidad de Pensiones</label>
                            <input type="number" class="form-control" id="pensiones" name="pensiones" value="<?php echo $programa['pensiones']; ?>" required>
                        </div>

                        <h4>Selecciona los cursos:</h4>
                        <div class="form-group">
                            <?php foreach ($cursos as $curso): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="curso_<?php echo $curso['id_curso']; ?>" name="curso[]" value="<?php echo $curso['id_curso']; ?>" 
                                    <?php echo (in_array($curso['id_curso'], array_column($programa_cursos, 'id_curso'))) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="curso_<?php echo $curso['id_curso']; ?>">
                                        <?php echo $curso['nombre_curso']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Modificar Programa</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
