<?php
require_once('../Models/ModificarMatriculaModel.php');

class ModificarMatriculaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ModificarMatriculaModel($pdo);
    }

    // Obtener estudiante con matrícula
    public function obtenerEstudianteConMatricula($id_estudiante) {
        return $this->model->obtenerEstudianteConMatricula($id_estudiante);
    }

    // Obtener detalles del programa
    public function obtenerProgramaDetalles($id_programa) {
        return $this->model->obtenerProgramaDetalles($id_programa);
    }

    // Obtener programas activos
    public function obtenerProgramas() {
        return $this->model->obtenerProgramasActivos();
    }

    // Procesar la actualización de matrícula
    public function procesarActualizacion($data, $id_estudiante) {
        $nuevosDatos = [
            'id_programa' => (int)$data['id_programa'],
            'monto_matricula' => (float)$data['monto_matricula'],
            'estado_matricula' => $data['estado_matricula'],
            'descuento' => ($data['beca'] == '1') ? (float)$data['descuento'] : 0
        ];

        return $this->model->actualizarMatricula($id_estudiante, $nuevosDatos);
    }
}
?>
