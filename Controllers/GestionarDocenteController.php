<?php
require_once __DIR__ . '/../Models/GestionarDocenteModel.php';

class GestionarDocenteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new GestionarDocenteModel($pdo);
    }

    // Obtener todos los docentes
    public function mostrarDocentes() {
        return $this->model->obtenerDocentes();
    }

    // Obtener un docente específico
    public function obtenerDocente($id_docente) {
        return $this->model->obtenerDocentePorId($id_docente);
    }

    // Registrar un nuevo docente
    public function registrarDocente($data) {
        return $this->model->registrarDocente($data);
    }

    // Actualizar los datos de un docente
    public function actualizarDocente($data) {
        return $this->model->actualizarDocente($data);
    }

    // Eliminar un docente
    public function eliminarDocente($id_docente) {
        return $this->model->eliminarDocente($id_docente)
            ? "Docente eliminado exitosamente."
            : "Error al eliminar el docente.";
    }
}
