<?php
require_once "../Models/DocenteModificarCalificacionModel.php";

class DocenteModificarCalificacionController {
    private $model;
    private $error = '';
    private $descripcion = '';
    private $calificaciones = [];
    public $mensaje = '';

    public function __construct(PDO $pdo, int $docenteId) {
        $this->model = new DocenteModificarCalificacionModel($pdo, $docenteId);
    }

    public function manejarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarFormulario();
        } elseif (isset($_GET['id_curso'], $_GET['id_programa'], $_GET['descripcion'])) {
            $this->descripcion = $_GET['descripcion'];
            $this->calificaciones = $this->model->obtenerCalificacionesPorDescripcion(
                (int)$_GET['id_curso'],
                (int)$_GET['id_programa'],
                $this->descripcion
            );
        }
    }

    private function procesarFormulario() {
        $idCurso = (int)$_POST['id_curso'];
        $idPrograma = (int)$_POST['id_programa'];
        $descripcionAnterior = $_POST['descripcion'];
        $descripcionNueva = trim($_POST['descripcion_nueva'] ?? '');
        $notas = $_POST['notas'] ?? [];

        if ($descripcionNueva === '') {
            $this->error = "La descripción no puede estar vacía";
            return;
        }

        foreach ($notas as $nota) {
            if (!is_numeric($nota) || $nota < 0 || $nota > 20) {
                $this->error = "Las calificaciones deben ser valores entre 0 y 20";
                return;
            }
        }

        $exito = $this->model->actualizarCalificacionesYDescripcion($idCurso, $idPrograma, $descripcionAnterior, $descripcionNueva, $notas);
        if ($exito) {
            $this->mensaje = "¡Calificaciones y descripción actualizadas correctamente!";
            // Actualizar descripción y recargar calificaciones para mostrar en la vista
            $this->descripcion = $descripcionNueva;
            $this->calificaciones = $this->model->obtenerCalificacionesPorDescripcion($idCurso, $idPrograma, $this->descripcion);
        } else {
            $this->error = "Error al actualizar las calificaciones";
        }
    }

    public function getError() { return $this->error; }
    public function getDescripcion() { return $this->descripcion; }
    public function getCalificaciones() { return $this->calificaciones; }
}
