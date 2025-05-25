<?php
require_once('../Models/ModificarEstudianteModel.php');

class ModificarEstudianteController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new ModificarEstudianteModel($pdo);
    }

    // Obtener estudiante por ID
    public function obtenerEstudiante($id) {
        return $this->model->obtenerEstudiantePorId($id);
    }

    // Actualizar datos del estudiante
    public function actualizarEstudiante($data) {
        return $this->model->actualizarEstudiante($data);
    }

    // Restablecer la contraseña con el DNI
    public function restablecerClave($id_estudiante) {
        return $this->model->restablecerClaveConDNI($id_estudiante);
    }
}
?>
