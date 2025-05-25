<?php
require_once "../Models/DocenteRegistrarAsistenciaModel.php";

class DocenteRegistrarAsistenciaController {
    private $model;
    public $cursos = [];
    public $programas = [];
    public $estudiantes = [];
    public $cursoSeleccionado = null;
    public $programaSeleccionado = null;
    public $fecha = null;
    public $mensaje = '';
    public $error = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteRegistrarAsistenciaModel($pdo, $docenteId);
    }

    public function cargarDatos() {
        // Establecer la zona horaria
        date_default_timezone_set('America/Lima');  // Ajusta según tu zona horaria
    
        // Obtener cursos
        $this->cursos = $this->model->obtenerCursos();
    
        if (isset($_GET['id_curso'])) {
            $this->cursoSeleccionado = intval($_GET['id_curso']);
            $this->programas = $this->model->obtenerProgramasPorCurso($this->cursoSeleccionado);
        }
    
        if (isset($_GET['id_programa'])) {
            $this->programaSeleccionado = intval($_GET['id_programa']);
        }
    
        // Verificar si se pasa una fecha en el GET, si no, tomar la fecha actual
        if (isset($_GET['fecha'])) {
            $this->fecha = $_GET['fecha'];
        } else {
            // Usa la fecha actual en formato YYYY-MM-DD
            $this->fecha = date('Y-m-d');  // Fecha correcta sin desfase
        }
    
        // Obtener estudiantes según curso y programa
        if ($this->cursoSeleccionado && $this->programaSeleccionado) {
            $this->estudiantes = $this->model->obtenerEstudiantesPorCursoYPrograma($this->cursoSeleccionado, $this->programaSeleccionado);
        }
    }

    public function procesarFormulario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->cursoSeleccionado = intval($_POST['id_curso']);
            $this->programaSeleccionado = intval($_POST['id_programa']);
            $this->fecha = $_POST['fecha'];
            $this->estudiantes = $this->model->obtenerEstudiantesPorCursoYPrograma($this->cursoSeleccionado, $this->programaSeleccionado);
        
            $asistencias = $_POST['asistencia'] ?? [];
        
            try {
                $this->model->beginTransaction();
            
                foreach ($this->estudiantes as $estudiante) {
                    $idEstudiante = $estudiante['id_estudiante'];
                    $estado = isset($asistencias[$idEstudiante]) && $asistencias[$idEstudiante] === 'Presente' ? 'Presente' : 'Ausente';
                    $this->model->guardarAsistencia($idEstudiante, $this->fecha, $estado, $this->cursoSeleccionado);
                }
            
                $this->model->commit();
            
                // Al finalizar, almacenar el mensaje de éxito en la sesión
                $_SESSION['mensaje'] = 'Asistencia registrada exitosamente.';
                $_SESSION['mensaje_tipo'] = 'success';  // Puedes usar 'success' o 'info' o cualquier otro tipo
    
                // Redirigir tras guardar exitosamente
                header('Location: ../Views/DocenteAsistencia.php');
                exit();
            
            } catch (Exception $e) {
                $this->model->rollBack();
                $this->error = "Error al registrar la asistencia: " . $e->getMessage();
            }
        }
    }
    
    
}
