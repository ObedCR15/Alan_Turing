<?php
require_once('../Models/RegistrarMatriculaModel.php');

class RegistrarMatriculaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new RegistrarMatriculaModel($pdo);
    }

    public function buscarEstudiantePorNombreApellido($nombre_apellido) {
        return $this->model->buscarEstudiantePorNombreApellido($nombre_apellido);
    }

    public function obtenerEstudiantePorId($id_estudiante) {
        return $this->model->obtenerEstudiantePorId($id_estudiante);
    }

    public function registrarMatricula($id_estudiante, $id_programa, $monto_matricula, $estado_matricula, $descuento) {
        if ($this->model->verificarMatriculaExistente($id_estudiante, $id_programa)) {
            return "❌ El estudiante ya está matriculado en este programa";
        }
        return $this->model->registrarMatricula($id_estudiante, $id_programa, $monto_matricula, $estado_matricula, $descuento);
    }

    public function obtenerProgramas() {
        return $this->model->obtenerProgramas();
    }

    public function obtenerProgramaDetalles($id_programa) {
        return $this->model->obtenerProgramaDetalles($id_programa);
    }
}
?>