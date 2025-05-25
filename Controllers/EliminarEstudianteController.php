<?php
require_once __DIR__ . '/../Models/EliminarEstudianteModel.php';

class EliminarEstudianteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new EliminarEstudianteModel($pdo);
    }

    public function eliminarEstudiante($id_estudiante) {
        return $this->model->eliminar($id_estudiante);
    }
}
