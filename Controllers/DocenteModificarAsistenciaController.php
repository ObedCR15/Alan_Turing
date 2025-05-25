<?php
require_once "../Models/DocenteModificarAsistenciaModel.php";
class DocenteModificarAsistenciaController {
    private $model;
    public $estudiantes = [];
    public $fecha = '';
    public $cursoSeleccionado = null;
    public $programaSeleccionado = null;
    public $mensaje = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteModificarAsistenciaModel($pdo, $docenteId);
    }

    public function cargarDatos() {
        // Verificar que los parámetros id_curso, id_programa y fecha estén presentes en la URL
        if (isset($_GET['id_curso']) && isset($_GET['id_programa']) && isset($_GET['fecha'])) {
            $this->cursoSeleccionado = intval($_GET['id_curso']);
            $this->programaSeleccionado = intval($_GET['id_programa']);
            $this->fecha = $_GET['fecha'];
        } else {
            $this->mensaje = "Faltan parámetros necesarios (curso, programa o fecha).";
            return;
        }

        // Obtener los estudiantes que están matriculados en el programa y curso seleccionado
        $this->estudiantes = $this->model->obtenerEstudiantesPorCursoYPrograma($this->cursoSeleccionado, $this->programaSeleccionado, $this->fecha);
    }

    public function modificarAsistencia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesamos los cambios de asistencia
            foreach ($_POST['asistencia'] as $idEstudiante => $estado) {
                $this->model->actualizarAsistencia($idEstudiante, $this->cursoSeleccionado, $this->programaSeleccionado, $this->fecha, $estado);
            }

            // Mensaje de éxito
            $this->mensaje = 'Asistencias actualizadas correctamente.';

            // Redirigir al docente a la página de "DocenteAsistencia.php" después de la modificación
            $_SESSION['mensaje'] = $this->mensaje;
            $_SESSION['mensaje_tipo'] = 'success';
            header('Location: DocenteAsistencia.php');
            exit;
        }
    }
}

?>
