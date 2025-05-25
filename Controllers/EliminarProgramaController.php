<?php
require_once('../Models/EliminarProgramaModel.php');

class EliminarProgramaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EliminarProgramaModel($pdo);
    }

    public function eliminar($id_programa) {
        return $this->model->eliminarPrograma($id_programa);
    }
}
?>
