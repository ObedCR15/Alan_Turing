<?php
require_once "../Models/DashboardEstudianteModel.php";

class DashboardEstudianteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new DashboardEstudianteModel($pdo);
    }

    public function cargarResumenEstudiante() {
        if (!isset($_SESSION['student_id'])) {
            header("Location: ../Views/LoginEstudiante.php");
            exit();
        }

        $idEstudiante = $_SESSION['student_id'];

        return [
            'totalPensiones' => $this->model->obtenerTotalPensiones($idEstudiante),
            'totalProgramas' => $this->model->obtenerTotalProgramas($idEstudiante),
        ];
    }
}
?>
