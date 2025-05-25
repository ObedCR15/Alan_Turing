<?php
// Incluir el modelo
require_once "../Models/EstudiantePagoModel.php";

class EstudiantePagoController {
    private $model;
    public $programas;

    // Constructor recibe la conexión PDO y la pasa al modelo
    public function __construct(PDO $pdo) {
        $this->model = new EstudiantePagoModel($pdo);
        $this->programas = [];
    }

    // Cargar los pagos del estudiante agrupados por programa
    public function cargarPagos() {
        if (!isset($_SESSION['student_id'])) {
            header("Location: ../Views/LoginEstudiante.php");
            exit();
        }

        $idEstudiante = $_SESSION['student_id'];
        $this->programas = $this->model->obtenerPagosEstudiante($idEstudiante);
    }
}
?>
