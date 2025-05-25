<?php
require_once __DIR__ . '/../Models/RegistrarEstudianteModel.php';

class RegistrarEstudianteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new RegistrarEstudianteModel($pdo);
    }

    public function generarNumeroMatricula(): string {
        return $this->model->generarNumeroMatricula();
    }

    public function registrar(array $data, ?string &$error = null): bool {
        try {
            return $this->model->registrar($data);
        } catch (PDOException $e) {
            $error = $e->getMessage();
            return false;
        }
    }
}
