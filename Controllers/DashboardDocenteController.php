<?php
require_once '../Models/DashboardDocenteModel.php';

class DashboardDocenteController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function cargarResumenDocente() {
        $model = new DashboardDocenteModel($this->pdo);
        return $model->obtenerResumenDocente($_SESSION['docente_id']);
    }
}
?>
