<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../Config/conexion.php');
require_once('../Controllers/RegistrarProgramaController.php');
require_once('../Models/RegistrarProgramaModel.php');

// Crear instancia del controlador
$model = new RegistrarProgramaModel($pdo);
$controller = new RegistrarProgramaController($model);

// Obtener los cursos disponibles
$cursos = $controller->getCursos();
$registroExitoso = false;

// Si se ha enviado el formulario, intentar registrar el programa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $registroExitoso = $controller->registerProgram();
    // Si el registro es exitoso, redirigir a la misma página
    if ($registroExitoso) {
        $_SESSION['mensaje'] = '¡Programa registrado exitosamente!';
        header("Location: GestionarProgramas.php");
        exit();
    } else {
        $_SESSION['mensaje'] = 'Hubo un error al registrar el programa.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Registrar Programa</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include('header.php'); ?>
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Registrar Nuevo Programa</h1>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-info">
                            <?php echo $_SESSION['mensaje']; ?>
                            <?php unset($_SESSION['mensaje']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="RegistrarPrograma.php" method="POST">
                        <div class="form-group">
                            <label for="nombre_programa">Nombre del Programa</label>
                            <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="costo">Costo de matrícula</label>
                            <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="pension">Pensión del Programa</label>
                            <input type="number" class="form-control" id="pension" name="pension" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required onchange="calcularDuracion()">
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required onchange="calcularDuracion()">
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración (semanas)</label>
                            <input type="number" class="form-control" id="duracion" name="duracion" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="pensiones">Cantidad de Pensiones</label>
                            <input type="number" class="form-control" id="pensiones" name="pensiones" readonly required>
                        </div>

                        <h4>Selecciona los cursos:</h4>
                        <div class="form-group">
                            <?php foreach ($cursos as $curso): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="curso_<?php echo $curso['id_curso']; ?>" name="curso[]" value="<?php echo $curso['id_curso']; ?>">
                                    <label class="form-check-label" for="curso_<?php echo $curso['id_curso']; ?>">
                                        <?php echo $curso['nombre_curso']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar Programa</button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Calcular duración en semanas y pensiones
        function calcularDuracion() {
            var fecha_inicio = new Date(document.getElementById('fecha_inicio').value);
            var fecha_fin = new Date(document.getElementById('fecha_fin').value);
            

            // Calcular duración en días y luego convertirla a semanas
            var duracion_dias = (fecha_fin - fecha_inicio) / (1000 * 60 * 60 * 24);
            if (duracion_dias < 0) {
                alert("La fecha de fin no puede ser anterior a la fecha de inicio.");
                return;
            }

            var duracion_semanas = Math.ceil(duracion_dias / 7); // Redondear a la semana más cercana
            document.getElementById('duracion').value = duracion_semanas;

            // Calcular las pensiones (1 pensión cada 4 semanas)
            var pensiones = Math.ceil(duracion_semanas / 4);  // Redondear hacia arriba
            document.getElementById('pensiones').value = pensiones;
        }
    </script>
</body>
</html>
