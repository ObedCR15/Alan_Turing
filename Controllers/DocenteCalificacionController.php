<?php
session_start();
require_once "../Models/DocenteCalificacionModel.php";

class DocenteCalificacionController {
    private $model;
    public $cursosProgramas = [];
    public $cursoSeleccionado = null;
    public $programaSeleccionado = null;
    public $calificaciones = [];

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteCalificacionModel($pdo, $docenteId);
    }

    public function cargarDatos() {
        $this->cursosProgramas = $this->model->obtenerCursosProgramas();

        if (isset($_GET['id_curso']) && ctype_digit($_GET['id_curso'])) {
            $this->cursoSeleccionado = (int) $_GET['id_curso'];
        }

        if (isset($_GET['id_programa']) && ctype_digit($_GET['id_programa'])) {
            $this->programaSeleccionado = (int) $_GET['id_programa'];
        }

        if ($this->cursoSeleccionado && $this->programaSeleccionado) {
            // Aquí usamos la versión agrupada por descripción
            $this->calificaciones = $this->model->obtenerCalificacionesAgrupadasPorDescripcion($this->cursoSeleccionado, $this->programaSeleccionado);
        }
    }
}
