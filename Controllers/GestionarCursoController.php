<?php
require_once __DIR__ . '/../Models/GestionarCursoModel.php';

class GestionarCursoController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new GestionarCursoModel($pdo);
    }

    public function mostrarCursos() {
        return $this->model->obtenerCursos();
    }

    public function mostrarDocentes() {
        return $this->model->obtenerDocentes();
    }

    public function registrarCurso($nombre, $descripcion, $id_docente) {
        return $this->model->registrarCurso($nombre, $descripcion, $id_docente);
    }

    public function eliminarCurso($id_curso) {
        return $this->model->eliminarCurso($id_curso)
            ? "Curso eliminado exitosamente."
            : "Error al eliminar el curso.";
    }

    public function obtenerCurso($id) {
        return $this->model->obtenerCursoPorId($id);
    }

    public function actualizarCurso($id, $nombre, $descripcion, $id_docente) {
        return $this->model->actualizarCurso($id, $nombre, $descripcion, $id_docente);
    }
}
?>
