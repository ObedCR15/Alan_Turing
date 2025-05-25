<?php
require_once('../Models/EliminarCursoModel.php');

class EliminarCursoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EliminarCursoModel($pdo);
    }

    // Eliminar un curso
    public function eliminarCurso($id) {
        if ($this->model->eliminarCurso($id)) {
            return "Curso eliminado exitosamente.";
        } else {
            return "Error al eliminar el curso.";
        }
    }
}
?>
