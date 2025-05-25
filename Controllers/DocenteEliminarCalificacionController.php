<?php
require_once "../Models/DocenteEliminarCalificacionModel.php";

class DocenteEliminarCalificacionController {
    private $model;
    private $docenteId;

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteEliminarCalificacionModel($pdo, $docenteId); // PASA los dos parámetros
        $this->docenteId = $docenteId;
    }

    public function eliminarCalificacion($idCurso, $idPrograma, $descripcion) {
        // NO session_start() aquí
        if ($this->model->eliminarCalificacionesPorDescripcion($idCurso, $idPrograma, $descripcion)) {
            $_SESSION['mensaje'] = "Calificaciones eliminadas correctamente.";
            $_SESSION['mensaje_tipo'] = 'success';
        } else {
            $_SESSION['mensaje'] = "Error al eliminar las calificaciones.";
            $_SESSION['mensaje_tipo'] = 'error';
        }
    }
}
