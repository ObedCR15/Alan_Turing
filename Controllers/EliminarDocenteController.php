<?php
require_once __DIR__ . '/../Models/EliminarDocenteModel.php';

class EliminarDocenteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new EliminarDocenteModel($pdo);
    }

    public function eliminar($id_docente) {
        return $this->model->eliminarPorId($id_docente)
            ? "Docente eliminado exitosamente."
            : "Error al eliminar el docente.";
    }
}
