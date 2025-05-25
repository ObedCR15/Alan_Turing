<?php
require_once('../Models/GestionarProgramasModel.php');

class GestionarProgramasController {
    private $model;

    public function __construct($pdo) {
        $this->model = new GestionarProgramasModel($pdo);
    }

    // Obtener todos los programas con sus cursos
    public function obtenerProgramas() {
        return $this->model->obtenerProgramasConCursos();
    }

    // Eliminar un programa
    public function eliminar($id_programa) {
        return $this->model->eliminarPrograma($id_programa);
    }
}
?>
