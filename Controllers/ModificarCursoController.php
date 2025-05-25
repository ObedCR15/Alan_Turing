<?php
require_once('../Models/ModificarCursoModel.php');

class ModificarCursoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ModificarCursoModel($pdo);
    }

    // Obtener un curso por ID
    public function obtenerCursoPorId($id) {
        return $this->model->obtenerCursoPorId($id);
    }

    // Modificar un curso
    public function modificarCurso($id, $nombre, $descripcion, $id_docente) {
        return $this->model->modificarCurso($id, $nombre, $descripcion, $id_docente);
    }

    // Obtener todos los docentes
    public function obtenerDocentes() {
        return $this->model->obtenerDocentes();
    }
}
?>
