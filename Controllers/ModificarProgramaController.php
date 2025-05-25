<?php
// ModificarProgramaController.php

class ModificarProgramaController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    // Obtener los cursos asociados a un programa
    public function getCursosAsociados($programa_id) {
        return $this->model->getCursosAsociados($programa_id);
    }

    // Obtener todos los cursos disponibles
    public function getCursos() {
        return $this->model->getCursos();
    }

    // Actualizar el programa
    public function updatePrograma($programa_id, $nombre_programa, $descripcion, $costo, $pension, $fecha_inicio, $fecha_fin, $duracion, $pensiones, $cursos) {
        $this->model->updatePrograma($programa_id, $nombre_programa, $descripcion, $costo, $pension, $fecha_inicio, $fecha_fin, $duracion, $pensiones);
        $this->model->deleteCursosAsociados($programa_id);
        $this->model->insertCursosAsociados($programa_id, $cursos);
    }
}
?>
