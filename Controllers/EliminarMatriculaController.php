<?php
require_once('../Models/EliminarMatriculaModel.php'); // Asegúrate de que la ruta sea correcta

class EliminarMatriculaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EliminarMatriculaModel($pdo);
    }

    // Método para eliminar la matrícula
    public function eliminarMatricula($id_estudiante, $id_programa) {
        // Llamamos al modelo para eliminar la matrícula
        return $this->model->eliminarMatricula($id_estudiante, $id_programa);
    }
}
?>
