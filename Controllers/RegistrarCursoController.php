<?php
require_once('../Config/conexion.php');
require_once('../Models/RegistrarCursoModel.php');

class RegistrarCursoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new RegistrarCursoModel($pdo);
    }

    public function registrarCurso($nombre, $descripcion, $id_docente) {
        return $this->model->registrarCurso($nombre, $descripcion, $id_docente);
    }

    public function obtenerDocentes() {
        return $this->model->obtenerDocentes();
    }
}
?>
