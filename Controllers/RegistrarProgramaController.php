<?php
class RegistrarProgramaController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    // Registrar el nuevo programa
    public function registerProgram() {
        if (
            isset($_POST['nombre_programa']) &&
            isset($_POST['descripcion']) &&
            isset($_POST['costo']) &&
            isset($_POST['pension']) &&
            isset($_POST['fecha_inicio']) &&
            isset($_POST['fecha_fin']) &&
            isset($_POST['duracion'])
        ) {
            // Recibir los datos del formulario
            $nombre_programa = $_POST['nombre_programa'];
            $descripcion = $_POST['descripcion'];
            $costo = $_POST['costo'];
            $pension = $_POST['pension'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $duracion = $_POST['duracion'];
            $cursos = isset($_POST['curso']) ? $_POST['curso'] : [];

            // Llamamos al modelo para registrar el programa
            $registrado = $this->model->registerProgram(
                $nombre_programa,
                $descripcion,
                $costo,
                $pension,
                $fecha_inicio,
                $fecha_fin,
                $duracion,
                $pensiones = ceil($duracion / 4), // Calcular las pensiones si la duración es mayor a 4 semanas
                $cursos
            );

            return $registrado;
        }
        return false;
    }

    // Obtener los cursos disponibles
    public function getCursos() {
        return $this->model->obtenerCursos();
    }
}
?>
