<?php

require_once "../Models/EstudianteCalificacionModel.php";

class EstudianteCalificacionController {
    private $model;
    public $programas = [];

    public function __construct(PDO $pdo) {
        $this->model = new EstudianteCalificacionModel($pdo);
    }

    public function cargarCalificaciones() {
        if (!isset($_SESSION['student_id'])) {
            header("Location: ../Views/LoginEstudiante.php");
            exit();
        }
        $idEstudiante = $_SESSION['student_id'];
        $this->programas = $this->model->obtenerCalificacionesEstudiante($idEstudiante);
    }
    public function obtenerDetalleFaltas(int $idEstudiante, int $idCurso): array {
        return $this->model->obtenerDetalleFaltas($idEstudiante, $idCurso);
    }
    
    
}
?>
