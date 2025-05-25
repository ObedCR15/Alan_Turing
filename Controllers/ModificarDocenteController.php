<?php
require_once __DIR__ . '/../Models/ModificarDocenteModel.php';

class ModificarDocenteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new ModificarDocenteModel($pdo);
    }

    public function obtenerDocente($id) {
        return $this->model->obtenerPorId($id);
    }

    public function actualizarDocente($data) {
        return $this->model->actualizar($data);
    }

    public function restablecerClave($id_docente) {
        return $this->model->restablecerClaveConDNI($id_docente);
    }
}
?>
