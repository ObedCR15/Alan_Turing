<?php
session_start();
require_once __DIR__ . '/../Config/conexion.php'; // Asegúrate que defines $pdo (PDO)
require_once __DIR__ . '/../Models/EstudianteMatriculaModel.php';

class EstudianteMatriculaController {
    private $model;
    public $estado;
    public $datosMatricula;

    public function __construct(PDO $pdo) {
        $this->model = new EstudianteMatriculaModel($pdo);
        $this->estado = 'no_matriculado'; // Valor por defecto
        $this->datosMatricula = null;
    }

    public function cargarEstado() {
        if (!isset($_SESSION['student_id'])) {
            header("Location: ../Views/LoginEstudiante.php");
            exit();
        }

        $idEstudiante = $_SESSION['student_id'];

        $this->estado = $this->model->estaMatriculado($idEstudiante) ? 'matriculado' : 'no_matriculado';

        if ($this->estado === 'matriculado') {
            $this->datosMatricula = $this->model->obtenerDatosMatricula($idEstudiante);
        }
    }
}

$controlador = new EstudianteMatriculaController($pdo);
$controlador->cargarEstado();
