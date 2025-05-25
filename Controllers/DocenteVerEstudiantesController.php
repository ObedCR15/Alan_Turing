<?php
require_once '../Models/DocenteVerEstudiantesModel.php';
class DocenteVerEstudiantesController {
    private $model;
    public $cursos = [];
    public $estudiantes = [];
    public $cursoSeleccionado = null;
    public $error = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteVerEstudiantesModel($pdo, $docenteId);
    }

    public function cargarDatos() {
        if (!isset($_SESSION['docente_id'])) {
            $this->error = "Sesión de docente no iniciada.";
            return;
        }
    
        $this->cursos = $this->model->obtenerCursos();
    
        if (isset($_GET['id_curso'])) {
            $this->cursoSeleccionado = intval($_GET['id_curso']);
            $estudiantes = $this->model->obtenerEstudiantesPorCurso($this->cursoSeleccionado);
    
            // Agrupar estudiantes por programa
            $this->estudiantes = [];
            foreach ($estudiantes as $est) {
                $programa = $est['nombre_programa'];
                if (!isset($this->estudiantes[$programa])) {
                    $this->estudiantes[$programa] = [];
                }
                $this->estudiantes[$programa][] = $est;
            }
        }
    }
    
}
