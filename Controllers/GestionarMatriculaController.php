<?php
require_once('../Models/GestionarMatriculaModel.php');

class GestionarMatriculaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new GestionarMatriculaModel($pdo);
    }

    public function mostrarMatriculas($id_programa = null) {
        $matriculas = $this->model->obtenerMatriculas($id_programa);
        
        // Calcular pensión con descuento para cada registro
        foreach ($matriculas as &$matricula) {
            $descuento = $matricula['descuento'] ?? 0;
            $pensionOriginal = $matricula['monto_pension'] ?? 0;
            
            $matricula['pension_con_descuento'] = $pensionOriginal * (1 - ($descuento / 100));
        }
        
        return $matriculas;
    }

    public function mostrarProgramas() {
        return $this->model->obtenerProgramas();
    }
}
?>