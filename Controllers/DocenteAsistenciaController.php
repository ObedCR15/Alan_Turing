<?php
require_once "../Models/DocenteAsistenciaModel.php";
class DocenteAsistenciaController {
    private $model;
    public $cursos = [];
    public $programas = [];
    public $fechas = [];
    public $resumenes = [];
    public $cursoSeleccionado = null;
    public $programaSeleccionado = null;
    public $mensaje = '';
    public $error = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteAsistenciaModel($pdo, $docenteId);
    }

    public function cargarDatos() {
        $this->cursos = $this->model->obtenerCursos();

        if (isset($_GET['id_curso'])) {
            $this->cursoSeleccionado = intval($_GET['id_curso']);
            $this->programas = $this->model->obtenerProgramasPorCurso($this->cursoSeleccionado);
        }

        if (isset($_GET['id_programa'])) {
            $this->programaSeleccionado = intval($_GET['id_programa']);
        }

        if (isset($_GET['fecha'])) {
            $this->fecha = $_GET['fecha'];
        } else {
            $this->fecha = date('Y-m-d');
        }

        if ($this->cursoSeleccionado && $this->programaSeleccionado) {
            $this->fechas = $this->model->obtenerFechasAsistenciaPorPrograma($this->cursoSeleccionado, $this->programaSeleccionado);
            $this->resumenes = [];
            foreach ($this->fechas as $fecha) {
                $this->resumenes[$fecha] = $this->model->resumenAsistenciaPorPrograma($this->cursoSeleccionado, $this->programaSeleccionado, $fecha);
            }
        }
    }
}



