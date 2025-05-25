<?php
require_once "../Models/DocenteNuevaCalificacionModel.php";
class DocenteNuevaCalificacionController {
    private $model;
    private $cursos = [];
    private $estudiantes = [];
    private $cursoSeleccionado = null;
    private $programaSeleccionado = null;
    private $descripcion = '';
    private $error = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteNuevaCalificacionModel($pdo, $docenteId);
        $this->cargarDatosIniciales();
    }

    private function cargarDatosIniciales() {
        $this->cursos = $this->model->obtenerCursosAsignadosDocente();
    }

    public function manejarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarFormulario();
        } else {
            $this->cargarEstudiantesSiAplica();
        }
    }

    private function procesarFormulario() {
        if (isset($_POST['guardarNotas'])) {
            $this->validarDatos();

            if (empty($this->error)) {
                $idCurso = intval($_POST['id_curso']);
                $idPrograma = intval($_POST['id_programa']);
                $this->descripcion = trim($_POST['descripcion']);
                $notas = $_POST['notas'] ?? [];

                $exito = $this->model->guardarCalificaciones($idCurso, $idPrograma, $notas, $this->descripcion);

                if ($exito) {
                    $_SESSION['mensaje'] = "¡Calificaciones registradas exitosamente!";
                    $_SESSION['mensaje_tipo'] = 'success';
                    header("Location: DocenteCalificacion.php");
                    exit();
                } else {
                    $this->error = "Error al guardar las calificaciones";
                }
            }
        }
    }

    private function cargarEstudiantesSiAplica() {
        if (!empty($_POST['id_curso']) && !empty($_POST['id_programa'])) {
            $this->cursoSeleccionado = intval($_POST['id_curso']);
            $this->programaSeleccionado = intval($_POST['id_programa']);
            $this->descripcion = $_POST['descripcion'] ?? '';
            $this->estudiantes = $this->model->obtenerEstudiantesMatriculados($this->cursoSeleccionado, $this->programaSeleccionado);
        }
    }

    private function validarDatos() {
        if (empty($_POST['descripcion']) || empty($_POST['notas'])) {
            $this->error = "Todos los campos son obligatorios";
            return;
        }

        foreach ($_POST['notas'] as $nota) {
            if (!is_numeric($nota) || $nota < 0 || $nota > 20) {
                $this->error = "Las notas deben ser valores entre 0 y 20";
                break;
            }
        }
    }

    // Getters
    public function getCursos() { return $this->cursos; }
    public function getEstudiantes() { return $this->estudiantes; }
    public function getCursoSeleccionado() { return $this->cursoSeleccionado; }
    public function getProgramaSeleccionado() { return $this->programaSeleccionado; }
    public function getDescripcion() { return $this->descripcion; }
    public function getError() { return $this->error; }
}
