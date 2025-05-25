<?php
require_once "../Models/DocenteEliminarAsistenciaModel.php";

class DocenteEliminarAsistenciaController {
    private $model;
    
    public function __construct(PDO $pdo) {
        $this->model = new DocenteEliminarAsistenciaModel($pdo);
    }

    public function eliminarAsistencia(int $idCurso, int $idPrograma, string $fecha) {
        try {
            $registrosEliminados = $this->model->eliminarAsistencia($idCurso, $idPrograma, $fecha);
            
            if ($registrosEliminados === false) {
                throw new Exception("Error en la operación de eliminación");
            }
            
            if ($registrosEliminados > 0) {
                $_SESSION['mensaje'] = "Se eliminaron $registrosEliminados registros de asistencia para la fecha $fecha";
                $_SESSION['mensaje_tipo'] = 'success';
            } else {
                $_SESSION['mensaje'] = "No se encontraron registros para eliminar";
                $_SESSION['mensaje_tipo'] = 'warning';
            }
            
            return true;
            
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error al eliminar: " . $e->getMessage();
            $_SESSION['mensaje_tipo'] = 'danger';
            return false;
        }
    }
}