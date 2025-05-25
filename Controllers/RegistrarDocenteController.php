<?php
require_once __DIR__ . '/../Models/RegistrarDocenteModel.php';

class RegistrarDocenteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new RegistrarDocenteModel($pdo);
    }

    public function registrarDocente($data, &$error = null) {
        try {
            return $this->model->registrar($data);
        } catch (PDOException $e) {
            $error = $e->getMessage();
            return false;
        }
    }
}
