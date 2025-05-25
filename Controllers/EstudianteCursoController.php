<?php
// Incluir el modelo
require_once "../Models/EstudianteCursoModel.php";

class EstudianteCursoController {
    private $model;
    public $programas;

    // Constructor recibe la conexión PDO y la pasa al modelo
    public function __construct(PDO $pdo) {
        $this->model = new EstudianteCursoModel($pdo);
        $this->programas = [];
    }

    // Función para cargar los cursos del estudiante agrupados por programa
    public function cargarCursos() {
        if (!isset($_SESSION['student_id'])) {
            header("Location: ../Views/LoginEstudiante.php");
            exit();
        }

        $idEstudiante = $_SESSION['student_id'];
        $this->programas = $this->model->obtenerCursosEstudiante($idEstudiante);
    }
}
?>
